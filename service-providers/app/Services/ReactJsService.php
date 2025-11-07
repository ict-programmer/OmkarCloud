<?php

namespace App\Services;

use App\Data\Request\Claude\CodegenData;
use App\Data\Request\ReactJs\ReactJsCodeForElementData;
use App\Data\Request\ReactJs\ReactJsCodeGenerationData;
use App\Data\Request\ReactJs\ReactJsMergeJsonData;
use App\Models\Element;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use App\Traits\MongoObjectIdTrait;

class ReactJsService
{
    use MongoObjectIdTrait;

    private mixed $projectStructure;

    public function mergeJson(ReactJsMergeJsonData $data): true
    {
        $this->projectStructure = $data->project_structure->design_json;
        Element::query()
            ->where('project_structure_id', $this->toObjectId($data->project_structure->id))
            ->get(['_id', 'project_structure_id', 'element_json'])
            ->each(fn ($element) => $element->update([
                $element->element_fused_json = [
                    'project_structure' => $this->projectStructure,
                    'element_json' => $element->element_json,
                ],
            ]));

        return true;
    }

    public function reactCodeForElement(ReactJsCodeForElementData $data): bool
    {
        $result = $this->callClaudeAI(
            $this->mainReactJsPrompt('This attached json contain the element json.'),
            [
                $this->buildAttachments($data->element->element_fused_json),
            ]
        );

        return $data->element->update([
            'element_code' => $result,
        ]);
    }

    private function callClaudeAI($prompt, $attachments): string
    {
        $data = CodegenData::from([
            'description' => $prompt,
            'attachments' => $attachments,
        ]);

        $elementCode = (new ClaudeAPIService())->codegen($data)->message;
        $pattern = '/^```[a-z]*\n/';
        $elementCode = preg_replace($pattern, '', $elementCode);
        return preg_replace('/```$/', '', $elementCode);
    }

    public function generateCodeCollecting(ReactJsCodeGenerationData $data): array
    {
        $elements = Element::query()
            ->where('project_structure_id', $this->toObjectId($data->project_structure->id))
            ->get(['_id', 'project_structure_id', 'element_code'])
            ->toArray();

        $files = [];
        foreach ($elements as $element) {
            $files[] = $this->buildAttachments($element['element_code']);
        }

        $prompt = '
        # Image link ' . $data->project_structure->design_link . '.
        # In attachments I passed many react codes for many elements, I need to merge between all those scripts to build one screen. 
        # I need result like image I sent in link. (Last js code must be like screenshot and all elements must be in one screen)
        # I DO NOT need any component in response. (NO import any component) Just native reactjs code. No component in ur generated code.
        # DON NOT skip any component in attachments. All should be written in generated code.
        # I just need reactjs code without any explanation or additional text. Just return the code as it is. No Description. No comments. No explanation. Just reactjs code.';
        $result = $this->callClaudeAI($prompt, $files);

        $name = $data->project_structure->design_id . '.tsx';
        file_put_contents(public_path($name), $result);

        return [
            'file_link' => env("APP_URL") . '/' . $name,
        ];
    }

    public function generateCodeDirectly(ReactJsCodeGenerationData $data): array
    {
        $elements = Element::query()
            ->where('project_structure_id', $this->toObjectId($data->project_structure->id))
            ->get(['_id', 'project_structure_id', 'element_json']);

        $json = $elements->pluck('element_json')->toArray();

        $prompt = '
        # Image link ' . $data->project_structure->design_link . '. Final Code should be like this image.
         ' . $this->reactPrompt();
        $result = $this->callClaudeAI($prompt, [
            'project_structure' => $this->buildAttachments($data->project_structure->design_json),
            'elements' => $this->buildAttachments($json),
        ]);

        $name = $data->project_structure->design_id . '.tsx';
        file_put_contents(public_path($name), $result);

        return [
            'file_link' => env("APP_URL") . '/' . $name,
        ];
    }

    private function generateReactComponent(array $elements): string
    {
        $output = '';

        foreach ($elements as $kE => $element) {
            Log::info('Indexing element: ' . $kE);
            $childrenCode = '';
            if (!empty($element['children'])) {
                $childrenCode = $this->generateReactComponent($element['children']);
            }

            $attachments = [
                $this->buildAttachments($this->projectStructure), // Full project design
                $this->buildAttachments($element['element_json']), // This element's JSON
            ];

            // Only add children JSX if it exists
            if (!empty(trim($childrenCode))) {
                $attachments[] = $this->buildAttachments($childrenCode); // JSX from children
            }

            $elementCode = $this->promptAIToGenerateReactComponent($attachments);

            $output = $elementCode;
        }

        return $output;
    }

    private function promptAIToGenerateReactComponent(array $attachments): string
    {
        $prompt = $this->reactPrompt();

        return $this->callClaudeAI($prompt, $attachments);
    }

    public function buildNestedJson(array $elements): array
    {
        $map = [];
        foreach ($elements as &$element) {
            $element['children'] = [];
            $map[$element['element_json']['id']] = &$element;
        }

        $tree = [];
        foreach ($elements as &$element) {
            $parentId = $element['parent_element_id'];

            if ($parentId && isset($map[$parentId])) {
                $map[$parentId]['children'][] = &$element;
            } else {
                $tree[] = &$element;
            }
        }

        return $tree;
    }

    private function buildAttachments($data): UploadedFile
    {
        $projectJson = json_encode($data, JSON_PRETTY_PRINT);
        $projectTempPath = tempnam(sys_get_temp_dir(), 'project_');
        file_put_contents($projectTempPath, $projectJson);

        return new UploadedFile(
            $projectTempPath,
            'project_structures.json',
            'application/json',
            null,
            true
        );
    }

    private function reactPrompt(): string
    {
        return "Json files is attached. The first JSON defines the overall project structure, and the second JSON contains many nested UI elements. These JSON files contain all the details about styles, layout, and component properties.

# Extraction Instructions:
- Parse the JSON to read component properties: text, visibility, padding, margin, colors, borders, radius, shadow, background, font, and font styles
- Respect the element hierarchy strictly — each element must be rendered inside its corresponding parent
- For each component, identify its children and embed their JSX directly in the correct spot in the parent layout
- Ensure each component includes relevant Tailwind and global styles (e.g., from globals.css)
- Use dimensions, spacing, alignment, and layout exactly as described in the JSON

# Typography and Assets:
- Use the `cn` utility for class merging (especially typography)
- Always use exact dimensions (e.g., 20x20 for icons) from the Figma JSON
- Import and use `Image` from `next/image` for image handling

# Component Structure:
- Add `'use client'` at the top of interactive components
- Use proper Next.js and TypeScript patterns
- Preserve layout, spacing, padding, and all design details pixel-perfectly from the JSON
- Maintain internal component nesting exactly as described in the hierarchy
- Do not generate isolated components for each child; instead, embed children directly inside their parents
- Every level of nesting should reflect the actual structure in the JSON

# Responsive and Dynamic Behavior:
- Implement responsive behavior as implied by layout or dimensions
- Add a `variant` prop for screens or modes if visible in the JSON
- Include props to control visibility, text content, and interactivity
- Use default prop values where applicable for flexible reuse

# Output Requirements:
- Return a single complete React component per hierarchy branch
- The final output must represent the parent layout with all its direct and nested children rendered inside
- Do NOT include multiple variants or customization unless defined
- Use TypeScript and provide full prop typing
- Include inline JSDoc comments for props
- Do not add example containers, page wrappers, or extra layout scaffolding

# Strict Compliance Notes:
1. Follow the component hierarchy from JSON without assumptions
2. Embed child JSX inside parent JSX (not separate components)
3. Use file paths, sizes, and styles exactly from the JSON
4. The output should contain only valid and complete JSX/TSX
5. The generated code should be production-ready and drop-in usable without editing

IMPORTANT: Do NOT output multiple components. Only return a single full component where children are embedded directly in their parent JSX according to the structure in the JSON.
IMPORTANT: Just add react code without any explanation or additional text. Do not add any comments or explanations. Just return the code as it is.
";
    }

    private function mainReactJsPrompt($append = ''): string
    {
        return   $append . "
        These JSON files contain all the details about styles, layout, and component properties.

# Extraction Instructions:
- Parse the JSON to read component properties: text, visibility, padding, margin, colors, borders, radius, shadow, background, font, and font styles
- Respect the element hierarchy strictly — each element must be rendered inside its corresponding parent
- For each component, identify its children and embed their JSX directly in the correct spot in the parent layout
- Ensure each component includes relevant Tailwind and global styles (e.g., from globals.css)
- Use dimensions, spacing, alignment, and layout exactly as described in the JSON

# Typography and Assets:
- Use the `cn` utility for class merging (especially typography)
- Always use exact dimensions (e.g., 20x20 for icons) from the Figma JSON
- Import and use `Image` from `next/image` for image handling

# Component Structure:
- Add `'use client'` at the top of interactive components
- Use proper Next.js and TypeScript patterns
- Preserve layout, spacing, padding, and all design details pixel-perfectly from the JSON
- Maintain internal component nesting exactly as described in the hierarchy
- Do not generate isolated components for each child; instead, embed children directly inside their parents
- Every level of nesting should reflect the actual structure in the JSON

# Responsive and Dynamic Behavior:
- Implement responsive behavior as implied by layout or dimensions
- Add a `variant` prop for screens or modes if visible in the JSON
- Include props to control visibility, text content, and interactivity
- Use default prop values where applicable for flexible reuse

# Output Requirements:
- Return a single complete React component per hierarchy branch
- The final output must represent the parent layout with all its direct and nested children rendered inside
- Do NOT include multiple variants or customization unless defined
- Use TypeScript and provide full prop typing
- Include inline JSDoc comments for props
- Do not add example containers, page wrappers, or extra layout scaffolding

# Strict Compliance Notes:
1. Follow the component hierarchy from JSON without assumptions
2. Embed child JSX inside parent JSX (not separate components)
3. Use file paths, sizes, and styles exactly from the JSON
4. The output should contain only valid and complete JSX/TSX
5. The generated code should be production-ready and drop-in usable without editing

IMPORTANT: Do NOT output multiple components. Only return a single full component where children are embedded directly in their parent JSX according to the structure in the JSON.
IMPORTANT: Just add react code without any explanation or additional text. Do not add any comments or explanations. Just return the code as it is.
";
    }
}