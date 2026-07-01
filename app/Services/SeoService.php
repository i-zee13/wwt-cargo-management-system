<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Organization;
use App\Models\Products;
use App\Models\SeoModel as Seo;
use Artesaos\SEOTools\Facades\JsonLdMulti;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\TwitterCard;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Session;

class SeoService extends BaseService
{
    /**
     * @param Model $post
     * @param $seo
     * @param null $metadata
     * @param \stdClass|null $modified_post_schema // maybe title, name not exists instead we have category_name, then pass it in $modified_post_schema
     * @return void
     */
    public function storeUpdateSeoData(Model $post, $request, $metadata = null, \stdClass $modified_post_schema = null): void
    {
        $seo = $request->seo;
        // dd($seo);
        $seo['meta_structure_tags'] = $request->meta_structure_tags;
        $existing_seo = Seo::query()->where([
            'seoable_type'     => get_class($post),
            'seoable_id'       => $post->id ?? $seo['model_id'],
            'post_type'        => $seo['schema_type'],
            'post_schema_type' => $seo['schema_type']
        ])->first();
        // facebook
        if (!empty($seo['og_image'])) { // meta_og_image = facebook_image
            if ($existing_seo && isset($existing_seo->og_image))
                $meta_og_image = $existing_seo->og_image;
            if (!empty($meta_og_image) && file_exists(public_path($meta_og_image))) {
                unlink(public_path($meta_og_image));
            }
            $meta_og_image = self::upload(schema_type: $seo['schema_type'], post: $post, file: $seo['og_image'], column: 'meta-og-image');
        } else {
            $meta_og_image = $seo['hidden_og_image'];
        }
        // dd($meta_og_image);
        // twitter
        if (!empty($seo['twitter_image'])) {
            if ($existing_seo && isset($existing_seo->twitter_image))
                $meta_twitter_image = $existing_seo->twitter_image;
            if (!empty($meta_twitter_image) && file_exists(public_path($meta_twitter_image))) {
                unlink(public_path($meta_twitter_image));
            }
            $meta_twitter_image = self::upload(schema_type: $seo['schema_type'], post: $post, file: $seo['twitter_image'], column: 'twitter_image');
        } else {
            $meta_twitter_image = $seo['hidden_twitter_image'];
        }

        if (empty($seo['seo_canonical_url'])) {
            if (!empty($seo['slug_prefix'])) { // e.h https://example.com/brands/prema => brands is a slug_prefix
                $canonical_url = config('app.url') . "/{$seo['slug_prefix']}/" . $post->slug;
            } else {
                $canonical_url = config('app.url') . "/" . $post->slug;
            }
        } else {
            $canonical_url = $seo['seo_canonical_url'];
        }


        // if ($seo['schema_type'] == Seo::SCHEMA_TYPE_PRODUCT) {
        //     $metadata = $this->createProductSchemaOrgMetadata(product_data: $post, schema_type: $seo['schema_type']);
        // }
        // dd($seo);
        $twitter_title =  $seo['seo_twitter_title'] ?? $seo['og_title'];

        $twitter_description =  $seo['seo_twitter_description'] ?? $seo['og_description'];
        if ($seo['seo_page_title']) {
            $title = $seo['seo_page_title'];
        } elseif ($modified_post_schema && property_exists($modified_post_schema, 'title')) {
            $title = $modified_post_schema->title;
        } else if ($post && $post->title) {
            $title = $post->title;
        } else {
            $title = $post->name;
        }
        // must define seo morph relation on that model like we have in Products model.
        $post->seo()->updateOrCreate([
            'seoable_type'      => get_class($post),
            'seoable_id'        => $post->id ?? $seo['model_id'],
            'post_type'         => $seo['schema_type'],
            'post_schema_type'  => $seo['schema_type'],
        ], [
            'post_type'                  =>  \Str::lower($seo['schema_type']),
            'post_title'                 =>  $title ?? '',
            'permalink'                  =>  $post->slug ?? \Str::slug($seo['seo_permalink']),
            'meta_keywords'              =>  $seo['meta_keywords'] ?? $post->meta_keywords ?? '',
            'meta_structure_tags'        =>  $seo['meta_structure_tags'] ?? $post->meta_structure_tags ?? '',
            'meta_description'           =>  $seo['seo_meta_description'] ?? $post->short_description ?? '',
            'og_image'                   =>  $meta_og_image ?? '',
            'og_title'                   =>  $seo['og_title'] ?? $post->short_description ?? '',
            'og_description'             =>  $seo['og_description'] ?? $post->short_description ?? '',
            'twitter_title'              =>  $twitter_title ?? $post->title ?? '',
            'twitter_meta_description'   =>  $twitter_description ?? $post->short_description ?? '',
            'twitter_thumbnail'          =>  $meta_twitter_image ?? $meta_og_image,
            'twitter_card_type'          =>  $seo['twitter_card_type'],
            'robot_index'                => ($seo['seo_robots_meta_index'] == 'index') ? 1 : 0, // 1-index, 0=noindex
            'robot_follow'               => ($seo['seo_robots_meta_follow'] == 'follow') ? 1 : 0, // 1-follow, 0=nofollow
            'robot_max_snippet'          =>  $seo['robot_max_snippet'] ?? null,
            'robot_max_video_preview'    =>  $seo['robot_max_video_preview'] ?? null,
            'robot_max_image_preview'    =>  $seo['robot_max_image_preview'] ?? null,
            'canonical_url'              =>  $canonical_url,
            'redirect'                   =>  $seo['redirect'] ?? null,
            'post_schema_type'           =>  $seo['schema_type'],
            // 'robot_nofollow'             =>  $seo['robot_nofollow'] ?? null,
            'robot_archive'              =>  $seo['robot_archive'] ?? null,
            'robot_no_image_index'       =>  $seo['robot_no_image_index'] ?? null,
            'robot_no_snippet'           =>  $seo['robot_no_snippet'] ?? null,
            'robot_no_snippet'           =>  $seo['robot_no_snippet'] ?? null,
            //'jsonld_metadata'          =>  $seo['jsonld_metadata'] ?? null,
            'metadata'                   =>  !empty($metadata) ? $metadata : null,
        ]);
    }

    public static function upload($schema_type, $post, $file, $column = 'thumbnail'): array|string
    {
        if (!empty($schema_type)) {
            if (!is_dir(storage_path("app/public/{$schema_type}"))) Storage::makeDirectory("/public/{$schema_type}", 0777, true);
        } else {
            if (!is_dir(storage_path('app/public/others'))) Storage::makeDirectory('/public/others', 0777, true);
        }
        $fullname = $file->getClientOriginalName();
        $filename = pathinfo($fullname, PATHINFO_FILENAME);
        $extension = pathinfo($fullname, PATHINFO_EXTENSION);
        $filename = self::image_thumbnail_name($schema_type, $post, $extension, $column);
        if (!empty($schema_type)) {
            $file = $file->storeAs("public/{$schema_type}", $filename);
        } else {
            $file = $file->storeAs('public/others', $filename);
        }
        return str_replace('public/', 'storage/', $file);
    }

    public static function image_thumbnail_name($schema_type, $post, $extension, $column): string
    {
        $post_name = str_replace(' ', '-', $post->name);
        if (!empty($sschema_type) && $schema_type == 'product') {
            $brand_name = str_replace(' ', '-', Brand::find($post->brand_id)->name);
            $sku = $post->sku;
            $thumbnail = $column;
            $image_name = $post_name . '-' . $brand_name . '-' . $sku . '-' . $thumbnail . '.' . $extension;
        } else {
            $thumbnail = $column;
            $image_name = $post_name . '-' . $thumbnail . '.' . $extension;
        }

        return $image_name;
    }

    /**
     * @param $seoable_type
     * @param $seoable_id
     * @return Model|Builder|Seo|null
     */
    public function getPostSeoData($seoable_type, $seoable_id): Model|Builder|Seo|null
    {
        return Seo::query()
            ->where(['seoable_type' => $seoable_type, 'seoable_id' => $seoable_id])
            ->first();
    }

    /**
     * @param Seo $seo
     * @return void
     */
    public function generateSinglePostSeoMetadata(Seo $seo): void
    {
        $organization  =   Organization::first();
        $metadata = !empty($seo->metadata) ? $seo->metadata : array();
        SEOMeta::setTitle($seo->post_title);
        SEOMeta::setDescription($seo->meta_description);
        if ($seo->robot_index == Seo::ROBOT_INDEX) {
            $index_value =  'index';
        } else if ($seo->robot_index == Seo::ROBOT_NOINDEX) {
            $index_value =  'noindex';
        }
        if ($seo->robot_follow == Seo::ROBOT_FOLLOW) {
            $follow_value =  'follow';
        } else if ($seo->robot_follow == Seo::ROBOT_NOFOLLOW) {
            $follow_value =  'nofollow';
        }
        $url = $seo->canonical_url;
        $seo->canonical_url = str_replace('//', '/', $url);
        SEOMeta::addMeta('robots', "{$index_value}, {$follow_value}");
        SEOMeta::setCanonical($seo->canonical_url);
        SEOMeta::addMeta('article:publisher', $organization->facebook_link);
        if ($seo->post_schema_type == Seo::SCHEMA_TYPE_PRODUCT) {
            SEOMeta::addMeta('product:availability', $metadata['product']['availability']);
        }
        SEOMeta::addKeyword($seo->meta_keywords);
        SEOTools::opengraph()->addProperty('type', 'website');

        OpenGraph::setDescription($seo->og_description ?? $seo->facebook_meta_description);
        // ->addKeyword($seo->meta_keywords);
        OpenGraph::setTitle($seo->og_title);
        OpenGraph::setUrl($seo->canonical_url);
        OpenGraph::addProperty('type', 'article');
        OpenGraph::addProperty('locale:alternate', ['en-Us']);
        OpenGraph::addImage(asset($seo->og_image), ['height' => 300, 'width' => 300]);

        // Get the file extension
        $extension = pathinfo($seo->og_image, PATHINFO_EXTENSION);
        SEOTools::opengraph()->addProperty('image:alt', '');
        SEOTools::opengraph()->addProperty('image:type', 'image/' . $extension);


        TwitterCard::setTitle($seo->twitter_title);
        TwitterCard::setDescription($seo->twitter_meta_description);
        TwitterCard::setType($seo->twitter_card_type);
        TwitterCard::setSite(config('app.url'));

        if ($organization->twitter_link) {
            TwitterCard::setSite($organization->twitter_link);
            TwitterCard::addValue('creator', $organization->twitter_link);
        }
        $twitter_img = $seo->twitter_thumbnail ?? $seo->og_image;
        TwitterCard::addValue('image', $twitter_img ?  url('/' . $twitter_img) : url('/templates/vapesuite/images/vape-suite.svg'));
        TwitterCard::addValue('label1', 'Time to read');
        TwitterCard::addValue('data1', 'Less than a minute');
        Session::put('meta_structure', $seo->meta_structure_tags);
        /*JsonLd::setTitle($seo->post_title);
        JsonLd::setDescription($seo->meta_description);
        JsonLd::setType($seo->post_type);
        JsonLd::addImage(asset($seo->facebook_thumbnail));*/
        JsonLdMulti::setTitle($seo->post_title);
        JsonLdMulti::setDescription($seo->meta_description);
        JsonLdMulti::setType($seo->post_type);
        JsonLdMulti::addImage(asset($seo->og_image), ['height' => 300, 'width' => 300]);
        if (!JsonLdMulti::isEmpty() && $seo->post_schema_type == Seo::SCHEMA_TYPE_PRODUCT) {
            JsonLdMulti::newJsonLd();
            JsonLdMulti::setType(\Str::ucfirst($seo->post_schema_type ?? 'Product'));
            JsonLdMulti::setTitle($seo->post_title);
            JsonLdMulti::setDescription($seo->meta_description);
            if (isset($metadata['product'], $metadata['product']['sku'])) {
                JsonLdMulti::addValue('sku', $metadata['product']['sku']);
            }
            if (isset($metadata['product'], $metadata['product']['brand'])) {
                JsonLdMulti::addValue('brand', $metadata['product']['brand']);
            }
            if (isset($metadata['product'], $metadata['product']['offers'], $metadata['product']['offers']['url'])) {
                JsonLdMulti::addValue('@id', $metadata['product']['offers']['url'] . "/#richSnippet");
            }
        }
        // AggregateOffer schema type
        if (!JsonLdMulti::isEmpty() && $seo->post_schema_type == Seo::SCHEMA_TYPE_PRODUCT) {
            if (isset($metadata['product'], $metadata['product']['offers'])) {
                if (isset($metadata['product']['offers']['type'])) {
                    JsonLdMulti::setType($metadata['product']['offers']['type']);
                }
                if (isset($metadata['product']['offers']['lowPrice'])) {
                    JsonLdMulti::addValue('lowPrice', $metadata['product']['offers']['lowPrice']);
                }
                if (isset($metadata['product']['offers']['highPrice'])) {
                    JsonLdMulti::addValue('highPrice', $metadata['product']['offers']['highPrice']);
                }
                if (isset($metadata['product']['offers']['offerCount'])) {
                    JsonLdMulti::addValue('offerCount', $metadata['product']['offers']['offerCount']);
                }
                if (isset($metadata['product']['offers']['priceCurrency'])) {
                    JsonLdMulti::addValue('priceCurrency', $metadata['product']['offers']['priceCurrency']);
                }
                if (isset($metadata['product']['offers']['availability'])) {
                    JsonLdMulti::addValue('availability', $metadata['product']['offers']['availability']);
                }
            }
        }
        if (!JsonLdMulti::isEmpty()) {
            JsonLdMulti::newJsonLd();
            JsonLdMulti::setType('Organization');
            JsonLdMulti::setTitle(config('app.name'));
        }
    }

    public function createProductSchemaOrgMetadata($product_data, $schema_type): \stdClass
    {
        $availability = Products::checkProductInstockByStatusId($product_data->status);
        $metadata = new \stdClass();
        $metadata->product = new \stdClass();
        $metadata->product->sku = $product_data->sku;
        $metadata->product->brand = Brand::query()->select('name')->find($product_data->brand_id)?->name;
        $metadata->product->availability = Str::lower($availability);
        $metadata->product->offers = new \stdClass();
        $metadata->product->offers->type = 'AggregateOffer';
        $metadata->product->offers->lowPrice = (string)$product_data->variants->min('sell_price');
        $metadata->product->offers->highPrice = (string)$product_data->variants->max('sell_price');
        $metadata->product->offers->offerCount = (string)$product_data->variants->count();
        $metadata->product->offers->priceCurrency = config('vapesuite.price.currency');
        $metadata->product->offers->availability = "https://schema.org/{$availability}";
        $metadata->product->offers->url = config('app.url') . '/' . $schema_type . '/' . $product_data->slug;

        return $metadata;
    }
}
