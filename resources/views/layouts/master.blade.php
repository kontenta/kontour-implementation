@inject('view_manager', 'Erik\AdminManager\Contracts\ViewManager')

@extends('admin::layouts.html')

@section('body')
  <main>
    <!-- Section {{ $view_manager->mainSection() }} -->
    @yield($view_manager->mainSection())
    <!-- End section {{ $view_manager->mainSection() }} -->
  </main>
@endsection

