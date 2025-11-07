<?php

namespace Database\Seeders;

use App\Http\Controllers\PerplexityController;
use App\Http\Requests\Perplexity\AcademicResearchRequest;
use App\Http\Requests\Perplexity\AiSearchRequest;
use App\Http\Requests\Perplexity\CodeAssistantRequest;
use App\Models\ServiceProvider;
use App\Traits\ServiceProviderSeederTrait;
use Illuminate\Database\Seeder;

class PerplexityServiceProviderSeeder extends Seeder
{
    use ServiceProviderSeederTrait;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceProvider = ServiceProvider::updateOrCreate(
            ['type' => 'Perplexity'],
            [
                'parameters' => [
                    'api_key' => 'YOUR_API_KEY',
                    'base_url' => 'https://api.perplexity.ai',
                    'version' => null,
                    'models_supported' => [
                        'sonar',
                        'sonar-pro',
                        'sonar-deep-research',
                        'sonar-reasoning',
                        'sonar-reasoning-pro',
                    ],
                    'features' => [
                        'ai_search',
                        'academic_research',
                        'code_assistant',
                    ],
                ],
                'is_active' => true,
                'controller_name' => PerplexityController::class,
            ]
        );

        $serviceTypes = [
            [
                'name' => 'AI Search',
                'input_parameters' => [
                    'model' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'sonar',
                        'options' => [
                            'source' => 'collection',
                            'collection_name' => 'service_provider_model',
                            'value_field' => 'model_name',
                            'label_field' => 'display_name',
                            'filters' => [
                                'service_provider_id' => $serviceProvider->id,
                                'status' => 'active',
                                'supports_search' => true,
                            ],
                            'fallback_options' => [
                                'sonar-pro',
                                'sonar',
                            ],
                        ],
                        'description' => 'Model name',
                        'userinput_rqd' => false
                    ],
                    'query' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 1,
                        'max_length' => 1000,
                        'description' => 'Search query text',
                        'default' => 'What is AI?',
                        'userinput_rqd' => true
                    ],
                    'search_type' => [
                        'type' => 'string',
                        'required' => true,
                        'default' => 'web',
                        'options' => [
                            'web',
                            'news',
                        ],
                        'description' => 'Type of search',
                        'userinput_rqd' => false
                    ],
                    'max_results' => [
                        'type' => 'integer',
                        'required' => false,
                        'default' => 10,
                        'min' => 0,
                        'max' => 100,
                        'description' => 'Maximum number of results',
                        'userinput_rqd' => true
                    ],
                    'temperature' => [
                        'type' => 'float',
                        'required' => false,
                        'default' => 0.2,
                        'min' => 0,
                        'max' => 1.9,
                        'description' => 'Temperature for search randomness (0 to <2)',
                        'userinput_rqd' => true
                    ],
                ],
                'response' => [
                    'id' => '4690f9e3-7342-4e56-bf6b-a8f47bdfd293',
                    'object' => 'chat.completion',
                    'created' => 1750940084,
                    'model' => 'sonar',
                    'systemFingerprint' => null,
                    'choices' => [
                        [
                            'index' => 0,
                            'message' => [
                                'role' => 'assistant',
                                'content' => 'Artificial Intelligence (AI) is a branch of computer science and technology focused on creating machines and software capable of performing tasks that typically require human intelligence. These tasks include learning, reasoning, problem-solving, perception, understanding natural language, decision-making, and creativity[1][4][6].

AI systems are designed to simulate human cognitive functions by learning from vast amounts of data, identifying patterns, and improving their performance over time without explicit programming for every specific task. This ability to learn and adapt distinguishes AI from traditional software that follows fixed instructions[1][6][8].

AI encompasses a broad range of technologies and disciplines, including machine learning, deep learning, natural language processing, computer vision, robotics, and more. It draws on fields such as computer science, data analytics, neuroscience, linguistics, psychology, and philosophy to build intelligent systems[1][4].

Common applications of AI today include:

- Virtual assistants like Siri, Alexa, and Google Assistant that understand and respond to human speech[4][8].
- Recommendation systems used by platforms like YouTube, Amazon, and Netflix to personalize content[4].
- Autonomous vehicles that can navigate and make decisions independently[4][8].
- Generative AI tools that create original text, images, and videos, such as ChatGPT[2][4][8].
- Image and speech recognition technologies[1][8].
- Automation of routine and complex tasks in business, healthcare, customer service, and more[1][5][8].

AI can be categorized into different levels:

- Artificial Narrow Intelligence (ANI): AI specialized in a single task, such as voice recognition or playing chess[7].
- Artificial General Intelligence (AGI): AI with human-like cognitive abilities across a wide range of tasks, still under development[4][7].
- Artificial Super Intelligence (ASI): A theoretical future AI that surpasses human intelligence in all respects[7].

In essence, AI is about making machines smarter by enabling them to learn from data and experience, thereby enhancing their ability to solve problems, interact naturally with humans, and perform tasks autonomously[1][2][6][8].',
                                'toolCalls' => [],
                                'functionCall' => null,
                            ],
                            'finishReason' => 'stop',
                        ],
                    ],
                    'usage' => [
                        'promptTokens' => 68,
                        'completionTokens' => 429,
                        'totalTokens' => 497,
                        'promptTokensDetails' => null,
                        'completionTokensDetails' => null,
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.choices[0].message.content',
                ],
                'request_class_name' => AiSearchRequest::class,
                'function_name' => 'aiSearch',
            ],
            [
                'name' => 'Academic Research',
                'input_parameters' => [
                    'model' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'sonar-deep-research',
                        'options' => [
                            'source' => 'collection',
                            'collection_name' => 'service_provider_model',
                            'value_field' => 'model_name',
                            'label_field' => 'display_name',
                            'filters' => [
                                'service_provider_id' => $serviceProvider->id,
                                'status' => 'active',
                                'supports_search' => true,
                            ],
                            'fallback_options' => [
                                'sonar-deep-research',
                            ],
                        ],
                        'description' => 'Model name',
                        'userinput_rqd' => false
                    ],
                    'query' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 1,
                        'max_length' => 1000,
                        'description' => 'Research query text',
                        'default' => 'Impact of artificial intelligence on healthcare',
                        'userinput_rqd' => true
                    ],
                    'search_type' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'academic',
                        'options' => [
                            'academic',
                        ],
                        'description' => 'Type of research',
                        'userinput_rqd' => false
                    ],
                    'max_results' => [
                        'type' => 'integer',
                        'required' => false,
                        'default' => 10,
                        'min' => 0,
                        'max' => 100,
                        'description' => 'Maximum number of results',
                        'userinput_rqd' => true
                    ],
                ],
                'response' => [
                    'id' => 'd0fcf7db-590f-46b6-868d-49ed3bae317a',
                    'object' => 'chat.completion',
                    'created' => 1750940228,
                    'model' => 'sonar-deep-research',
                    'systemFingerprint' => null,
                    'choices' => [
                        [
                            'index' => 0,
                            'message' => [
                                'role' => 'assistant',
                                'content' => '<think>
Okay, the user is asking about the impact of AI on healthcare. This is a broad topic with significant real-world implications. Let me structure this systematically.

First, I need to cover the major areas where AI is making a difference in healthcare. The key domains seem to be diagnostics (especially medical imaging), drug discovery, administrative efficiency, and personalized medicine. 

For diagnostics, there\'s strong evidence about AI in radiology - I recall studies from Nature showing AI matching radiologists in some tasks. Should mention specific examples like mammography and retinal scans. The Lancet article about dermatology AI is also relevant here.

Drug discovery is another major area. Need to highlight how AI accelerates target identification - that Nature Biotechnology review would be good to cite. The cost reduction stats from McKinsey are striking too.

Administrative applications are less glamorous but crucial. Those JAMA Internal Medicine studies about clinician burnout are important context. The 30% time savings figure from Nuance is compelling evidence.

Personalized treatment planning deserves its own section. The NEJM study on chemotherapy optimization and the Nature Medicine paper about radiotherapy algorithms show concrete benefits. Should emphasize how this moves us beyond one-size-fits-all medicine.

But can\'t ignore the challenges. Algorithmic bias is a real concern - that Science paper about dermatology AI performing worse on darker skin tones is critical to mention. Data privacy issues with GDPR/HIPAA too. And the human factor - clinicians needing to understand these tools.

The conclusion should balance optimism with caution. Maybe quote that JAMA viewpoint about augmentation versus replacement. The WHO guidelines provide good framing about responsible implementation.

Need to maintain academic rigor while keeping it accessible. The user might be a researcher or student based on the scholarly focus. Should structure with clear sections but avoid being too dry. Those citation boxes will help readability while maintaining credibility.
</think>
## Impact of Artificial Intelligence on Healthcare

Artificial intelligence is fundamentally transforming healthcare delivery, research, and patient outcomes. Below is an evidence-based analysis of its key impacts:

### I. Enhanced Diagnostics and Medical Imaging
- **Improved Accuracy**: AI algorithms analyze medical images (X-rays, MRIs, CT scans) with precision matching or exceeding human experts. A *Nature* study found AI detected breast cancer in mammograms with comparable accuracy to radiologists ([McKinney et al., 2020](https://www.nature.com/articles/s41586-019-1799-6)).
- **Early Detection**: AI identifies subtle patterns invisible to humans. For diabetic retinopathy, Google\'s algorithm achieved 90% sensitivity in early detection ([Gulshan et al., JAMA 2016](https://jamanetwork.com/journals/jama/fullarticle/2588762)).
- **Workflow Efficiency**: AI prioritizes critical cases, reducing radiologist workload by 30% in emergency settings ([RSNA 2022 report](https://pubs.rsna.org/doi/10.1148/ryai.210317)).

### II. Accelerated Drug Discovery & Development
- **Target Identification**: AI analyzes genomic data to identify disease targets 50-60% faster than traditional methods ([Nature Biotechnology, 2023](https://www.nature.com/articles/s41587-023-01769-w)).
- **Clinical Trial Optimization**: AI predicts patient recruitment likelihood and optimizes trial design, reducing development costs by ~$1.3B per drug ([McKinsey, 2022](https://www.mckinsey.com/industries/life-sciences/our-insights/ai-in-biopharma-research)).

### III. Administrative Efficiency
- **Automated Documentation**: NLP tools like Nuance DAX reduce clinician documentation time by 50% ([JAMA Internal Medicine, 2023](https://jamanetwork.com/journals/jamainternalmedicine/fullarticle/2800904)).
- **Prior Authorization**: AI systems process insurance approvals 70% faster, reducing administrative waste ([Health Affairs, 2021](https://www.healthaffairs.org/doi/10.1377/hlthaff.2020.01816)).

### IV. Personalized Medicine
- **Treatment Optimization**: IBM Watson analyzes patient data to recommend personalized cancer therapies, improving treatment response rates by 30% in lymphoma cases ([NEJM Evidence, 2022](https://evidence.nejm.org/doi/10.1056/EVIDoa2200085)).
- **Predictive Analytics**: AI models predict patient deterioration 6-24 hours earlier than traditional methods ([Nature Medicine, 2021](https://www.nature.com/articles/s41591-021-01593-2)).

### V. Emerging Challenges
- **Algorithmic Bias**: Studies reveal racial bias in dermatology AI tools, with 34% lower accuracy for darker skin tones ([Science, 2022](https://www.science.org/doi/10.1126/science.abd6464)).
- **Data Privacy**: HIPAA-compliant federated learning is emerging to train AI without sharing raw patient data ([Nature Digital Medicine, 2023](https://www.nature.com/articles/s41746-023-00868-x)).
- **Clinical Integration**: Only 35% of AI solutions achieve sustained clinical adoption due to workflow misalignment ([JAMIA Open, 2022](https://academic.oup.com/jamiaopen/article/5/1/ooac007/6548308)).

## Conclusion
AI is revolutionizing healthcare through enhanced diagnostics, accelerated research, operational efficiency, and personalized care. However, successful integration requires addressing ethical concerns, reducing bias, and ensuring clinician-AI collaboration. As stated in *The Lancet Digital Health*: "AI won\'t replace clinicians, but clinicians using AI will replace those who don\'t" ([Davenport & Kalakota, 2019](https://www.thelancet.com/journals/landig/article/PIIS2589-7500(19)30023-8/fulltext)). Future advancements depend on responsible implementation guided by WHO\'s ethics framework for AI in health ([WHO, 2021](https://www.who.int/publications/i/item/9789240029200)).',
                                'toolCalls' => [],
                                'functionCall' => null,
                            ],
                            'finishReason' => 'stop',
                        ],
                    ],
                    'usage' => [
                        'promptTokens' => 68,
                        'completionTokens' => 1261,
                        'totalTokens' => 1329,
                        'promptTokensDetails' => null,
                        'completionTokensDetails' => null,
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.choices[0].message.content',
                ],
                'request_class_name' => AcademicResearchRequest::class,
                'function_name' => 'academicResearch',
            ],
            [
                'name' => 'Code Assistant',
                'input_parameters' => [
                    'model' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'sonar-reasoning',
                        'options' => [
                            'source' => 'collection',
                            'collection_name' => 'service_provider_model',
                            'value_field' => 'model_name',
                            'label_field' => 'display_name',
                            'filters' => [
                                'service_provider_id' => $serviceProvider->id,
                                'status' => 'active',
                                'supports_search' => true,
                            ],
                            'fallback_options' => [
                                'sonar-reasoning',
                                'sonar-reasoning-pro',
                            ],
                        ],
                        'description' => 'Model name',
                        'userinput_rqd' => false
                    ],
                    'query' => [
                        'type' => 'string',
                        'required' => true,
                        'min_length' => 1,
                        'max_length' => 1000,
                        'description' => 'Coding question or prompt',
                        'default' => 'How to reverse a string in Python?',
                        'userinput_rqd' => true
                    ],
                    'programming_language' => [
                        'type' => 'string',
                        'required' => false,
                        'description' => 'Programming language (e.g., python, javascript)',
                        'default' => 'python',
                        'userinput_rqd' => true
                    ],
                    'code_length' => [
                        'type' => 'string',
                        'required' => false,
                        'default' => 'medium',
                        'options' => [
                            'short',
                            'medium',
                            'long',
                        ],
                        'description' => 'Desired length of generated code',
                        'userinput_rqd' => false
                    ],
                ],
                'response' => [
                    'id' => '54958087-53c6-4728-bf21-6ffe5bd00e62',
                    'object' => 'chat.completion',
                    'created' => 1750940372,
                    'model' => 'sonar-reasoning',
                    'systemFingerprint' => null,
                    'choices' => [
                        [
                            'index' => 0,
                            'message' => [
                                'role' => 'assistant',
                                'content' => '<think>
Okay, let\'s tackle how to reverse a string in Python. The user wants a medium-sized code snippet with clean formatting and comments. First, I need to recall the common methods for reversing strings in Python. From the search results, the main approaches are using slicing [::-1], the reversed() function with join(), creating a custom loop, and maybe even the list reverse method, though strings are immutable.

The most efficient and Pythonic way is slicing. So step one is to include that method. The code would be something like s[::-1]. Then, maybe include the reversed() approach with join, since that\'s another popular method. Let\'s add that as a second method.

Wait, the user asked for a medium code snippet. So including two different methods would be good. Let\'s start with slicing. Then maybe the reversed with join. Then perhaps a loop method, either for or while loop. The search results mention using a for loop by building the string in reverse. Let\'s include that.

Also, the user wants clean code with comments. Each method should be in a separate function with docstrings explaining what they do. Maybe have a main function that tests all methods.

Looking at the search results, the examples from W3Schools show slicing as the first method. DigitalOcean mentions slicing and loops. Real Python discusses reversed() and loops. So the three main methods here are slicing, reversed with join, and a loop (for or while).

Since the code needs to be medium-sized, including three methods would be sufficient. Let\'s structure the code with three functions: one for slicing, one for reversed with join, and one for the loop method. Each with a docstring. Then a main function that tests them with a sample string.

Wait, but in Python, strings can\'t be reversed in place because they\'re immutable. So when using reversed(), you have to convert it back to a string. So for reversed, it\'s \'\'.join(reversed(s)).

So the code structure would be:

def reverse_slice(s):
    return s[::-1]

def reverse_reversed(s):
    return \'\'.join(reversed(s))

def reverse_loop(s):
    result = \'\'
    for char in s:
        result = char + result
    return result

Then in the main, test all three.

But the user might want comments explaining each method. So each function should have a comment or docstring explaining the approach.

Also, include a test string, like "Hello World", and print the results.

Alternatively, maybe combine them into a class or use helper functions. But keeping it simple as separate functions is better for clarity.

Another consideration: the loop method could be time-consuming for very long strings, but for the purpose of this example, it\'s acceptable.

Now, putting it all together with proper formatting and comments.

Make sure the code is well-commented, uses clear variable names, and includes example usage.

So the final code would look something like this:

First, the slicing function. Then the reversed with join. Then the loop method. Then a main function to test them.

Include docstrings in each function to explain what they do.

Yes, that should cover the main methods. Let\'s draft the code accordingly.
</think>

To reverse a string in Python, there are several efficient methods. Below are three common approaches with explanations.

## Method 1: String Slicing (Most Efficient)
```python
def reverse_string_slicing(s: str) -> str:
    """Reverse string using slicing (step -1).
    Example:
        Input: \'Hello\'
        Process: s[::-1] → starts from end, moves backward in 1 step
    """
    return s[::-1]

# Usage
print(reverse_string_slicing("Hello"))  # Output: \'olleH\' [1][2][4]
```

## Method 2: `reversed()` + `join()` (Pythonic Approach)
```python
def reverse_string_reversed(s: str) -> str:
    """Reverse string using reversed() iterator and join all characters.
    Example:
        reversed(\'Python\') → iterator of \'n\', \'o\', \'h\', etc.
    """
    return \'\'.join(reversed(s))

# Usage
print(reverse_string_reversed("Python"))  # Output: \'nohtyP\' [5][6]
```

## Method 3: Character Reassembly (Loop Approach)
```python
def reverse_string_loop(s: str) -> str:
    """Build reversed string by prepending characters.
    Example:
        Input: \'Test\' → \'\' → \'T\' + \'est\' = \'Tes\' → \'st\' → \'st\' + \'T\' = \'stT\'
    """
    result = \'\'
    for char in s:
        result = char + result  # Prepend each character
    return result

# Usage
print(reverse_string_loop("Test"))  # Output: \'stT\' [4][5]
```

## Main Usage Example
```python
if __name__ == "__main__":
    original = "Reverse This!"
    print("[Slicing]:", reverse_string_slicing(original))
    print("[reversed+join]:", reverse_string_reversed(original))
    print("[Loop]:", reverse_string_loop(original))
```

**Performance Note**:  
Slicing (`[::-1]`) is the fastest method due to underlying optimizations, making it best for large strings[3]. The `reversed()` approach is memory-efficient for iterable processing, while loops are better suited for educational purposes or custom logic[4][5].',
                                'toolCalls' => [],
                                'functionCall' => null,
                            ],
                            'finishReason' => 'stop',
                        ],
                    ],
                    'usage' => [
                        'promptTokens' => 47,
                        'completionTokens' => 1141,
                        'totalTokens' => 1188,
                        'promptTokensDetails' => null,
                        'completionTokensDetails' => null,
                    ],
                ],
                'response_path' => [
                    'final_result' => '$.choices[0].message.content',
                ],
                'request_class_name' => CodeAssistantRequest::class,
                'function_name' => 'codeAssistant',
            ],
        ];

        $keptServiceTypeIds = $this->processServiceTypes($serviceProvider, $serviceTypes, 'Perplexity');

        $deletedProviderTypeCount = $this->cleanupObsoleteServiceTypes($serviceProvider, $keptServiceTypeIds);

        $this->command->info('Cleanup completed:');
        $this->command->info("- Deleted {$deletedProviderTypeCount} obsolete service provider types");
        $this->command->info('- Kept ' . count($keptServiceTypeIds) . ' service types for Perplexity');
    }
}
