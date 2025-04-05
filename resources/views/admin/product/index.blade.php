@extends('layouts.admin')

@section('content')
<div class="wrapper">

    @include('admin.auth.header')
    @include('admin.auth.sidebar')

    <div class="content-wrapper">

        <section class="content-header">
            <div class="container-fluid">
                <h1>All Products</h1>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>SKU</th>
                            <th>Name</th>
                            <th>Detail</th>
                            <th>Price</th>
                            <th>Image</th>
                            <th>Created</th>
                            <th>Updated</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->sku }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->detail }}</td>
                            <td>${{ $product->price }}</td>
                            <td>
                                <img src="{{ asset('storage/products/' . $product->image) }}" width="60" alt="">
                            </td>
                            <td>{{ $product->created_at }}</td>
                            <td>{{ $product->updated_at }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </section>

    </div>

    @include('admin.auth.footer')

</div>
@endsection
