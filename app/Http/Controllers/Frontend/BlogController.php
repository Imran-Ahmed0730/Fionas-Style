<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Frontend\BlogService;

class BlogController extends Controller
{
    protected $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    public function index(Request $request)
    {
        $categorySlug = $request->get('category');

        $data['blogs'] = $this->blogService->getBlogs(9, $categorySlug);
        $data['recent_blogs'] = $this->blogService->getRecentBlogs(4);
        $data['categories'] = $this->blogService->getBlogCategories();

        return view('frontend.blog.index', $data);
    }

    public function blogDetail($slug)
    {
        $data['blog'] = $this->blogService->getBlogBySlug($slug);
        $data['recent_blogs'] = $this->blogService->getRecentBlogs(4);
        $data['categories'] = $this->blogService->getBlogCategories();

        return view('frontend.blog.details', $data);
    }
}
