<!-- show details of a blog post -->
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header">{{ $blog->title }}</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <h5>Author: {{ $blog->user->name }}</h5>
                    <p>{{ $blog->content }}</p>

                    @if ($blog->image)
                        <img src="{{ asset("storage/" . $blog->image) }}" alt="Blog Image" class="img-thumbnail" style="max-width: 100%;">
                    @endif

                    @auth
                        <hr>
                        <p>Status:
                        @if ($blog->status === 'pending')
                            <span class="badge bg-warning">Pending</span>
                        @elseif ($blog->status === 'published')
                            <span class="badge bg-success">Published</span>
                        @elseif ($blog->status === 'rejected')
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                        </p>

                        @if (Auth::user()->id === $blog->user_id)
                            <a href="{{ route('blogs.edit', $blog->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('blogs.destroy', $blog->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        @elseif (Auth::user()->role === 'admin')
                            @if ($blog->status === 'pending')
                            <form action="{{ route('blogs.change-status', ['id' => $blog->id, 'status' => 'published']) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success btn-sm">Publish</button>
                            </form>
                            <form action="{{ route('blogs.change-status', ['id' => $blog->id, 'status' => 'rejected']) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                            </form>
                            @elseif ($blog->status === 'published')
                            <form action="{{ route('blogs.change-status', ['id' => $blog->id, 'status' => 'pending']) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-warning btn-sm">Unpublish</button>
                            </form>
                            @endif
                        @endif
                    @endauth
                    <br/>
                    @guest
                    <a href="{{ route('blogs.index') }}" class="btn btn-secondary mt-3">Back to Blogs</a>
                    @else
                        @if (Auth::user()->role === 'user')
                        <a href="{{ route('blogs.my-blogs') }}" class="btn btn-secondary mt-3">Back to My Blogs</a>
                        @elseif (Auth::user()->role === 'admin')
                        <a href="{{ route('blogs.all-blogs') }}" class="btn btn-secondary mt-3">Back to All Blogs</a>
                        @endif
                    @endguest
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
