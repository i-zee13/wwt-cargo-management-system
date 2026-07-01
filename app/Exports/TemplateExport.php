<?php

namespace App\Exports;

use App\Models\Products;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TemplateExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $columns;
    protected $sub_category;

    public function __construct($columns)
    {
        $this->columns = $columns;
    }
    /**
     * @return Collection
     */
    public function collection()
    {
        die;
        $products   =   Products::selectRaw('id, sku, name AS product_name, (SELECT name FROM brands WHERE id = brand_id) AS brand, short_description, description AS long_description, key_features, seo')->where('sub_category_id', $this->sub_category->id)->get();
        $products   =   $products->map(function ($product) {
            $product['key_features']        =   implode(', ', json_decode($product->key_features));
            $seo                            =   !empty($product->seo) ? json_decode($product->seo) : '';
            $product['page_title']          =   isset($seo->page_title) ? $seo->page_title : '';
            $product['meta_tag_name']       =   isset($seo->meta_tag_name) ? $seo->meta_tag_name : '';
            $product['meta_keywords']       =   isset($seo->meta_keywords) ? $seo->meta_keywords : '';
            $product['meta_description']    =   isset($seo->meta_description) ? $seo->meta_description : '';
            $product['meta_og_title']       =   isset($seo->meta_og_title) ? $seo->meta_og_title : '';
            $product['meta_og_description'] =   isset($seo->meta_og_description) ? $seo->meta_og_description : '';
            $product['meta_structure_tags'] =   isset($seo->meta_structure_tags) ? $seo->meta_structure_tags : '';
            unset($product['seo']);
            return $product;
        });
        return $products;
    }

    public function headings(): array
    {
        return $this->columns;
    }
}
