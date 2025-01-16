<x-app-layout>
    <center>
        <div style="width:80%; margin-top:30px;">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if (\Session::has('success'))
                <div class="alert alert-success">
                    <p>{{ \Session::get('success') }}</p>
                </div>
            @endif
        </div>
    </center>

    <center>
        <div style="margin: 50px;">
            <form method="POST" action="{{ route('createclinic') }} " enctype="multipart/form-data"
                style="background-color: #d4edda; border-radius: 15px; padding: 30px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); width: 40%;">
                @csrf
                <h2 style="text-align: center; color: #343a40; margin-bottom: 20px;">Create a New Clinic</h2>

                <!-- Clinic Name Input -->
                <label for="clinic_name" style="font-size: 18px; color: #343a40;">New Clinic Name</label><br>
                <input type="text" name="clinic_name" id="clinic_name"
                    style="width: 100%; padding: 10px; font-size: 16px; border-radius: 8px; border: 1px solid #ced4da; margin-bottom: 20px;"
                    required><br>

                <!-- Upload CSV -->
                <label for="csv_file" style="font-size: 18px; color: #343a40;">Upload CSV File:</label>
                <input type="file" name="csv_file" id="csv_file" required
                    style="width: 100%; padding: 10px; font-size: 16px; border-radius: 8px; border: 1px solid #ced4da; margin-bottom: 20px;">

                <!-- Submit Button -->
                <button type="submit" class="btn btn-success"
                    style="width: 100%; padding: 12px; font-size: 18px; background-color: #28a745; color: white; border: none; border-radius: 8px; cursor: pointer;">Create
                    Clinic</button>
            </form>
        </div>
    </center>

    <!-- Font Awesome for copy icon -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- Include FontAwesome Icons -->
</x-app-layout>
