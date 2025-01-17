<!-- resources/views/home.blade.php -->
@extends('sb-admin-2.layouts.app')

@section('content')

<style>
li {
    color: black;
}
</style>
<div class="jumbotron">
    <h1 style="color:black">Dashboard Food Admin</h1>
</div>

<!-- Content Row -->
<div class="row">

</div>

@if(session('success'))
<script>
    alert('{{ session('success') }}');
</script>
@endif
@if(session('error'))
<script>
    alert('{{ session('error') }}');
</script>
@endif

@endsection