@extends('layouts.master')
@section('title') Branch Management @endsection
@section('css')
    @livewireStyles
@endsection
@section('content')
    @livewire('branches.branch-management')
@endsection

@section('js')
    @livewireScripts
    <script>
        $(document).ready(function() {
            Livewire.on('closeModal', () => {
                const modalIds = ['createBranchModal', 'editBranchModal'];

                modalIds.forEach(id => {
                    const modalElement = document.getElementById(id);
                    
                    if (modalElement) {
                        const modalInstance = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                        modalInstance.hide();
                    }
                });
            });
        });
    </script>
@endsection