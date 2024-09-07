@extends('layouts.master')
@section('title') Services Management @endsection

@section('content')
    @livewire('services.service-management')
@endsection

@section('js')
    
    <script>
        $(document).ready(function() {
            Livewire.on('closeModal', () => {
                const modalIds = ['createServiceModal', 'editServiceModal'];

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