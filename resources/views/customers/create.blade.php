<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Add Customer</title>
        <!-- Link compiled Bootstrap -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-light">
        <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow p-4 rounded" style="min-width: 400px; max-width: 600px; width: 100%;">

        <h2 class="mb-4 text-center text-primary">Add a New Customer</h2>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success')}}
            </div>
        @endif

        {{-- Display Validation Errors --}}
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form --}}
        <div id="form-messages"></div>
        <form id="customer-form" action="{{ route('customers.store') }}" method="POST">
            @csrf {{-- Prevents CSRF --}}
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label><br>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{old('name')}}">
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email:</label><br>
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                id="email" name="email" value="{{old('email')}}">
            </div>

            <br>
            <div class="form-group">
                <label for="phone_number" class="form-label">Phone Number:</label><br>
                <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{old('phone_number')}}">
            </div>

            <br>
            <div class="form-group">
                <label for="date_of_birth" class="form-label">Date of Birth:</label><br>
                <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                id="date_of_birth" name="date_of_birth" value="{{old('date_of_birth')}}">
            </div>

            <br><br>
            <button type="submit" class="btn btn-primary w-100">Submit</button>
        </form>
        </div>
        </div>

        <script>
            console.log('Form script loaded');
            
            document.addEventListener('DOMContentLoaded', () => {
                const form = document.getElementById('customer-form');
                const messages = document.getElementById('form-messages');
            
                const clearFeedback = () => {
                    messages.innerHTML = '';
                    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                    document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
                    document.querySelectorAll('.alert').forEach(el => el.remove());
                };
            
                const showSuccess = (message) => {
                    messages.innerHTML = `<div class="alert alert-success">${message}</div>`;
                };
            
                const showErrors = (errors) => {
                    let output = '<div class="alert alert-danger"><ul>';
                    for (const key in errors) {
                        const field = document.querySelector(`[name="${key}"]`);
                        const message = errors[key][0];
                        if (field) {
                            field.classList.add('is-invalid');
            
                            const feedback = document.createElement('div');
                            feedback.className = 'invalid-feedback';
                            feedback.innerText = message;
                            field.parentNode.appendChild(feedback);
                        }
                        output += `<li>${message}</li>`;
                    }
                    output += '</ul></div>';
                    messages.innerHTML = output;
                };
            
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    clearFeedback();
            
                    const formData = new FormData(form);
            
                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: formData
                        });
            
                        const data = await response.json();
            
                        if (response.ok) {
                            showSuccess(data.message || 'Customer added successfully!');
                            form.reset();
                        } else {
                            showErrors(data.errors || {});
                        }
            
                    } catch (error) {
                        messages.innerHTML = `<div class="alert alert-danger">Something went wrong. Try again.</div>`;
                    }
                });
            });
            </script>
            
    </body>
</html>