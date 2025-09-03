<?php
// database/seeders/OmkarCloudMapsSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceProvider;
use App\Models\ServiceType;
use App\Models\ServiceProviderType;

class OmkarCloudMapsSeeder extends Seeder {
  public function run(): void {
    $provider = ServiceProvider::updateOrCreate(
      ['type' => 'OmkarCloudMapsScraper'],
      ['parameter' => [
        'api_url' => env('OMKAR_MAPS_BASE_URL'),
        'api_key' => env('OMKAR_MAPS_API_KEY'),
      ]]
    );

    $names = [
      'Business Search by Query','Search by Links','Scrape Reviews',
      'Output Result Status','Detailed Result View','Export to JSON/CSV/Excel',
      'Task Management','Filtered Search','Sort by Ads/Reviews/Website'
    ];
    $types = [];
    foreach ($names as $n) { $types[$n] = ServiceType::firstOrCreate(['name'=>$n]); }

    $features = [
      'Business Search by Query'     => 'business_search_by_query',
      'Search by Links'              => 'search_by_links',
      'Scrape Reviews'               => 'scrape_reviews',
      'Output Result Status'         => 'output_result_status',
      'Detailed Result View'         => 'detailed_result_view',
      'Export to JSON/CSV/Excel'     => 'export_to_json/csv/excel',
      'Task Management'              => 'task_management',
      'Filtered Search'              => 'filtered_search',
      'Sort by Ads/Reviews/Website'  => 'sort_by_ads/reviews/website',
    ];

    foreach ($features as $name=>$feature) {
      ServiceProviderType::updateOrCreate(
        ['service_provider_id'=>$provider->_id, 'service_type_id'=>$types[$name]->_id],
        ['parameters'=>['feature'=>$feature,'sample'=>true],'seed'=>1]
      );
    }
  }
}
