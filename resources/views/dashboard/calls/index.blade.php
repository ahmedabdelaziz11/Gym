@extends('layouts.master')
@section('title') Calls Management @endsection

@section('content')
    @livewire('calls.call-management')
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            Livewire.on('closeModal', () => {
                const modalIds = ['createCallModal', 'editCallModal','CallFeedBackModal'];

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