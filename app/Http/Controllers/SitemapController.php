<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>';
        $sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        
        // Homepage
        $sitemap .= $this->addUrl(route('home'), now(), '1.0', 'daily');
        
        // Static pages
        $sitemap .= $this->addUrl(route('about'), now(), '0.8', 'weekly');
        $sitemap .= $this->addUrl(route('contact'), now(), '0.8', 'weekly');
        $sitemap .= $this->addUrl(route('advertise'), now(), '0.7', 'monthly');
        
        // Property listings
        $sitemap .= $this->addUrl(route('properties.sale'), now(), '0.9', 'daily');
        $sitemap .= $this->addUrl(route('properties.rent'), now(), '0.9', 'daily');
        $sitemap .= $this->addUrl(route('properties.corporate'), now(), '0.8', 'daily');
        $sitemap .= $this->addUrl(route('properties.by-owner'), now(), '0.8', 'daily');
        
        // Individual properties
        $properties = Property::where('status', 1)->get();
        foreach ($properties as $property) {
            $sitemap .= $this->addUrl(
                route('properties.show', $property), 
                $property->updated_at, 
                '0.7', 
                'weekly'
            );
        }
        
        $sitemap .= '</urlset>';
        
        return response($sitemap, 200)->header('Content-Type', 'text/xml');
    }
    
    private function addUrl($loc, $lastmod, $priority, $changefreq)
    {
        return '<url>' .
            '<loc>' . $loc . '</loc>' .
            '<lastmod>' . $lastmod->format('Y-m-d') . '</lastmod>' .
            '<priority>' . $priority . '</priority>' .
            '<changefreq>' . $changefreq . '</changefreq>' .
            '</url>';
    }
}