@extends('layouts.master')
@section('title') User Management @endsection

@section('content')
    @livewire('users.user-management')
@endsection

@section('js')    
    <script>
        $(document).ready(function() {
            Livewire.on('closeModal', () => {
                const modalIds = ['createUserModal', 'editUserModal'];

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