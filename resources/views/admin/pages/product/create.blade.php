@extends('admin.layout.master')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Product</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Product</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <ul>
          @foreach($errors->all() as $item)
            <li> {{ $item }}</li>
          @endforeach
        </ul>
        <div class="row">
            <div class="col-md-12">
              <!-- general form elements -->
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Product</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="{{ route('admin.product.store') }}" 
                enctype="multipart/form-data"
                method="POST">
                  <div class="card-body">
                    <div class="form-group">
                      <label for="name">Name</label>
                      <input value="{{ old('name') }}" type="text" name="name" class="form-control" id="name" placeholder="Enter name">
                      @error('name')
                        <small class="text-danger">{{ $message }}</small>
                      @enderror
                    </div>
                    <div class="form-group">
                      <label for="name">Price</label>
                      <input value="{{ old('price') }}" type="number" name="price" class="form-control" id="price" placeholder="Enter price">
                      @error('price')
                        <small class="text-danger">{{ $message }}</small>
                      @enderror
                    </div>
                    <div class="form-group">
                      <label for="name">Description</label>
                      <textarea name="description" id="description" cols="30" rows="10">{{ old('description') }}</textarea>
                      @error('description')
                        <small class="text-danger">{{ $message }}</small>
                      @enderror
                    </div>
                    <div class="form-group">
                      <label for="name">Image</label>
                      <input type="file" name="image" class="form-control" id="image">
                      @error('image')
                        <small class="text-danger">{{ $message }}</small>
                      @enderror
                    </div>
                    <div class="form-group">
                      <label for="image_gallery">Image Gallery</label>
                      <input type="file" multiple name="image_gallery[]" class="form-control" id="image_gallery">
                      @error('image_gallery')
                        <small class="text-danger">{{ $message }}</small>
                      @enderror
                    </div>
                    <div class="form-group">
                      <label for="product_category_id">Product Category</label>
                      <select name="product_category_id" class="custom-select" id="product_category_id">
                        <option value="">---Please Select---</option>
                        @foreach($productCategories as $productCategory)
                          <option value="{{ $productCategory->id }}">{{ $productCategory->name }}</option>
                        @endforeach
                      </select>
                      @error('status')
                        <small class="text-danger">{{ $message }}</small>
                      @enderror
                      </div>
                    <div class="form-group">
                      <label for="status">Status</label>
                      <select name="status" class="custom-select" id="status">
                        <option value="">---Please Select---</option>
                        <option {{ old('status') == '1' ? 'selected' : '' }} value="1">Show</option>
                        <option {{ old('status') == '0' ? 'selected' : '' }} value="0">Hide</option>
                      </select>
                      @error('status')
                        <small class="text-danger">{{ $message }}</small>
                      @enderror
                      </div>
                  </div>
                  <!-- /.card-body -->
                  @csrf
                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Create</button>
                  </div>
                </form>
              </div>
              <!-- /.card -->
            </div>
          </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
@endsection

@section('my-script')
<script>
  $(document).ready(function (){
    $('#name').on('keyup', function(){
        var nameInput = $(this).val();
        
        $.ajax({
           method: "POST", //method of form
           url: "{{ route('admin.product_category.slug') }}", //action of form
           data: { name: nameInput, _token: "{{ csrf_token() }}" }, //input name
           success: function (response){
              $('#slug').val(response.slug);
           },
           fail: function(){
              alert('Something went wrong with ajax slug');
           }
        });
    });

    ClassicEditor
        .create( document.querySelector( '#description' ),{
          ckfinder: {
              uploadUrl: '{{route('admin.product.image.upload').'?_token='.csrf_token()}}',
          }
        } )
        .catch( error => {
            console.error( error );
        } );
  });
</script>
@endsection