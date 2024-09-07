@extends('layouts.master')
@section('title') Clients Management @endsection

@section('content')
    @livewire('clients.client-management')
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            Livewire.on('closeModal', () => {
                const modalIds = ['editClientModal','CallFeedBackModal'];

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