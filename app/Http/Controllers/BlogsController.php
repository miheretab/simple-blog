<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Blog;
use Illuminate\Support\Facades\Auth;

class BlogsController extends Controller
{

    /**
     * Display a listing of published blogs.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch published blogs
        $blogs = Blog::published()->paginate(1);
        return view('blogs.show_all', compact('blogs'));
    }

    /**
     * Show a single blog.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function detail($id)
    {
        // Show a single blog
        $blog = Blog::where(['id' => $id, 'status' => Blog::STATUS_PUBLISHED])->first();
        // Check if the blog exists and belongs to the authenticated user
        if (!$blog) {
            return redirect()->route('blogs.index')->with('error', 'Blog not found.');
        }

        return view('blogs.show', compact('blog'));
    }

    //for auth users functions
    /**
     * Show form to create a new blog.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Show form to create a new blog
        return view('blogs.create');
    }

    /**
     * Store a new blog.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate and store the new blog
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',//max 1MB
        ]);

        $blog = new Blog();
        $blog->title = $request->title;
        $blog->content = $request->content;
        if ($request->hasFile('image')) {
            $blog->image = $request->file('image')->store('images', 'public');
        }
        $blog->user_id = auth()->id();
        $blog->save();

        return redirect()->route('blogs.my-blogs')->with('success', 'Blog created successfully.');
    }

    /**
     * Show a single blog.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Show a single blog
        $blog = Blog::findOrFail($id);
        // Check if the blog exists and belongs to the authenticated user
        if ($blog->user->id != auth()->id() && auth()->user()->role != 'admin') {
            return redirect()->route('blogs.my-blogs')->with('error', 'Blog not found.');
        }

        return view('blogs.show', compact('blog'));
    }

    /**
     * Show form to edit an existing blog if it is pending or rejected.
     * allowed only for blog author
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        // Show form to edit an existing blog
        $blog = Blog::where(['id' => $id, 'user_id' => auth()->id()])->first();

        if (!$blog || ($blog->status !== Blog::STATUS_PENDING && $blog->status !== Blog::STATUS_REJECTED)) {
            return redirect()->route('blogs.show', $id)->with('error', 'You can only edit your own pending or rejected blogs.');
        }

        return view('blogs.edit', compact('blog'));
    }

    /**
     * Update an existing blog.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validate and update the blog
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',//max 1MB
        ]);

        $blog = Blog::where(['id' => $id, 'user_id' => auth()->id()])->first();

        if (!$blog || ($blog->status !== Blog::STATUS_PENDING && $blog->status !== Blog::STATUS_REJECTED)) {
            return redirect()->route('blogs.my-blogs')->with('error', 'You can only edit your own pending or rejected blogs.');
        }

        $blog->title = $request->title;
        $blog->content = $request->content;
        $blog->status = Blog::STATUS_PENDING; // Default to pending when updated
        if ($request->hasFile('image')) {
            $blog->image = $request->file('image')->store('images', 'public');
        }
        $blog->save();

        return redirect()->route('blogs.my-blogs')->with('success', 'Blog updated successfully.');
    }

    /**
     * Delete a blog.
     * allowed only for blog author
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Delete a blog
        $blog = Blog::where(['id' => $id, 'user_id' => auth()->id()])->first();

        if (!$blog || ($blog->status !== Blog::STATUS_PENDING && $blog->status !== Blog::STATUS_REJECTED)) {
            return redirect()->route('blogs.show', $id)->with('error', 'You can only delete your own pending or rejected blogs.');
        }

        $blog->delete();

        return redirect()->route('blogs.my-blogs')->with('success', 'Blog deleted successfully.');
    }

    /**
     * Display blogs created by the authenticated user.
     *
     * @return \Illuminate\View\View
     */
    public function myBlogs()
    {
        // Fetch blogs created by the authenticated user
        $blogs = \App\Models\Blog::where('user_id', auth()->id())->get();
        return view('blogs.index', compact('blogs'));
    }

    //admin actions
    public function allBlogs()
    {
        $blogs = \App\Models\Blog::all();
        return view('blogs.index', compact('blogs'));
    }

    /**
     * Change the status of a blog.
     *
     * @param int $id
     * @param string $status
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changeStatus($id, $status = 'published')
    {
        // Publish a blog
        $blog = \App\Models\Blog::findOrFail($id);
        $blog->status = $status;

        if ($status === 'published') {
            $blog->published_at = now();
        } else {
            $blog->published_at = null;
        }

        $blog->save();

        return redirect()->route('blogs.all-blogs')->with('success', 'Blog ' . $status . ' successfully.');
    }

}
