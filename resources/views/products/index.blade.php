@extends('layouts.app')

@section('content')

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products</h1>
    </div>


    <div class="card">
        <form action="{{'search'}}" method="post" class="card-header">
            @csrf
            <div class="form-row justify-content-between">
                <div class="col-md-2">
                    
                    <input type="text" name="title" placeholder="Product Title" class="form-control">
                </div>
                <div class="col-md-2">
                    <select name="variant" id="" class="form-control">
                        

                    
                    </select>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Price Range</span>
                        </div>
                        <input type="text" name="price_from" aria-label="First name" placeholder="From" class="form-control">
                        <input type="text" name="to" aria-label="Last name" placeholder="To" class="form-control">
                    </div>
                </div>
                <div class="col-md-2">
                    <input type="date" name="date" placeholder="Date" class="form-control">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="btn btn-primary float-right"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </form>

        <div class="card-body">
            <div class="table-response">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Variant</th>
                        <th width="150px">Action</th>
                    </tr>
                    </thead>

                    <tbody>
                        @if(!empty($products))
                        @foreach ($products as $product)
                    <tr>
                        <td>{{$loop->iteration}}</td>
                        <td>{{$product->title}} <br> Created at : {{ $product->created_at->format('d-M-Y') }}
                        </td>
                        <td>{{ Str::limit($product->description, 50) }} </td>
                        <td>
                           
                            <dl class="row mb-0 " style="height: 80px; overflow: hidden" id="variant{{$product->id}}">
                            @if(!empty($product->product_variant_price))
                            @foreach ($product->product_variant_price as $p_variant)
                            

                                <dt class="col-sm-3 pb-0">
                                  
                                    {{$p_variant->price_v_one?$p_variant->price_v_one->variant:''}} 
                                    {{$p_variant->price_v_two?'/ '.$p_variant->price_v_two->variant:''}}
                                    {{$p_variant->price_v_three?'/ '.$p_variant->price_v_three->variant:''}}
                                    
                                </dt>
                                <dd class="col-sm-9">
                                    <dl class="row mb-0">
                                        <dt class="col-sm-4 pb-0">Price : {{ number_format(($p_variant?$p_variant->price:0),2) }}</dt>
                                        <dd class="col-sm-8 pb-0">InStock : {{ number_format(($p_variant?$p_variant->stock:0),2) }}</dd>
                                    </dl>
                                </dd>
                                @endforeach
                                @endif
                            </dl>
                           
                            
                            <button onclick="$('#variant{{$product->id}}').toggleClass('h-auto')" class="btn btn-sm btn-link">Show more</button>
                           
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('product.edit', 1) }}" class="btn btn-success">Edit</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @endif

                    </tbody>

                </table>
            </div>

        </div>

        <div class="card-footer">
            <div class="row justify-content-between">
                <div class="col-md-6">
                    <p>Showing {{ $products->firstItem() }} to {{ $products->lastItem() }}
                        out of {{$products->total()}}</p>
                </div>
                <div class="col-md-2">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
   

@endsection

