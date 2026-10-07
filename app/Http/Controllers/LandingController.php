<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class LandingController extends Controller
{
    public function index()
    {
        $newsItems = [];
        try {
            $response = Http::timeout(5)->get('https://www.vaticannews.va/en.rss.xml');
            if ($response->successful()) {
                $xml = simplexml_load_string($response->body(), 'SimpleXMLElement', LIBXML_NOCDATA);
                if ($xml && isset($xml->channel->item)) {
                    foreach ($xml->channel->item as $item) {
                        $mediaUrl = null;
                        $media = $item->children('http://search.yahoo.com/mrss/');
                        if (isset($media->content)) {
                            $mediaUrl = (string)$media->content->attributes()->url;
                        }
                        
                        $descriptionHtml = (string)$item->description;
                        $plainDesc = trim(strip_tags(preg_replace('/<a\b[^>]*>(.*?)<\/a>/i', '', $descriptionHtml)));
                        $plainDesc = Str::limit($plainDesc, 140);
                        
                        $link = (string)$item->link;
                        
                        $category = 'Vatican';
                        if (str_contains($link, '/pope/')) {
                            $category = 'Pope';
                        } elseif (str_contains($link, '/church/')) {
                            $category = 'Church';
                        } elseif (str_contains($link, '/world/')) {
                            $category = 'World';
                        }
                        
                        $newsItems[] = [
                            'title' => trim((string)$item->title),
                            'description' => $plainDesc,
                            'link' => $link,
                            'pubDate' => (string)$item->pubDate,
                            'date_formatted' => date('M d, Y', strtotime((string)$item->pubDate)),
                            'category' => $category,
                            'image' => $mediaUrl ?: 'https://images.unsplash.com/photo-1548625149-fc4a29cf7092?auto=format&fit=crop&w=800&q=80',
                        ];
                    }
                }
            }
        } catch (\Throwable $e) {
            $newsItems = [];
        }

        // Fallback default news if offline/empty
        if (empty($newsItems)) {
            $newsItems = [
                [
                    'title' => 'Pope on Amoris laetitia: What Church do people meet when most fragile?',
                    'description' => 'Pope Leo XIV encourages Bishops from around the world to share what has emerged in their Churches over the ten years since Amoris laetitia.',
                    'link' => 'https://www.vaticannews.va/en.html',
                    'pubDate' => date('r'),
                    'date_formatted' => date('M d, Y'),
                    'category' => 'Pope',
                    'image' => 'https://images.unsplash.com/photo-1548625149-fc4a29cf7092?auto=format&fit=crop&w=800&q=80',
                ],
                [
                    'title' => 'Pope for Mission Sunday: Evangelisation is always a work of love',
                    'description' => 'In a video message for World Mission Sunday, the Holy Father thanks the faithful for their prayers and sustained support.',
                    'link' => 'https://www.vaticannews.va/en.html',
                    'pubDate' => date('r'),
                    'date_formatted' => date('M d, Y'),
                    'category' => 'Vatican',
                    'image' => 'https://images.unsplash.com/photo-1519817650390-64a93db51149?auto=format&fit=crop&w=800&q=80',
                ],
                [
                    'title' => 'Pope: Mary is safe refuge amidst life difficulties',
                    'description' => 'At the General Audience, the Holy Father renews his invitation to pray the Rosary for world peace.',
                    'link' => 'https://www.vaticannews.va/en.html',
                    'pubDate' => date('r'),
                    'date_formatted' => date('M d, Y'),
                    'category' => 'Church',
                    'image' => 'https://images.unsplash.com/photo-1579783902614-a3fb3927b675?auto=format&fit=crop&w=800&q=80',
                ],
            ];
        }

        return view('welcome', compact('newsItems'));
    }
}
