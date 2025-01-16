<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
    <center>
        <div style="width:80%;margin-top:30px">
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
    <div class="m-10">
        <form method="POST" action="{{route('bulktransfer')}}" enctype="multipart/form-data">
            @csrf
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <!-- Clinic Dropdown -->
                <div style="flex: 1; margin-right: 10px;">
                    <label for="clinics">Choose a Clinic</label><br>
                    <select name="clinics" id="clinics" style="width: 100%;" required>
                        <?php $clinics = DB::table('clinics')->get('clinic_name'); ?>
                        <option value="" disabled selected>Select a clinic</option>
                        @foreach ($clinics as $clinic)
                            <option value="{{ $clinic->clinic_name }}">{{ $clinic->clinic_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- File Upload -->
                <div>
                    <label for="item_pdf">Upload PDF</label><br>
                    <input type="file" id="item_pdf" name="item_pdf" accept="application/pdf" style="width: 100%;"><br>
                    @error('item_pdf')
                        <p style="color:red;size:13px">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Dynamic Inputs for Drugs and Quantities -->
            <div id="bulk-distribution-container">
                <!-- Template for Inputs -->
                <div class="bulk-distribution-row" style="display: flex; margin-bottom: 10px;">
                    <!-- Drug Dropdown -->
                    <div style="flex: 2; margin-right: 10px;">
                        <label for="drug_name[]">Drug Name</label><br>
                        <select name="drug_name[]" class="drug-dropdown" style="width: 100%;" required>
                            <?php $drugs = DB::table('stock_items')->get(); ?>
                            <option value="" disabled selected>Select a drug</option>
                            @foreach ($drugs as $drug)
                                <option value="{{ $drug->item_number }}" data-item-number="{{ $drug->item_number }}">{{ $drug->item_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Quantity Input -->
                    <div style="flex: 1; margin-right: 10px;">
                        <label for="quantity[]">Quantity</label><br>
                        <input type="number" name="quantity[]" min="1" placeholder="Enter quantity"
                            style="width: 100%;" required>
                    </div>

                    <!-- Remove Button -->
                    <button type="button" class="remove-row"
                        style="background-color: red; color: white; border: none; padding: 5px;">
                        Remove
                    </button>
                </div>
            </div>

            <!-- Add More Button -->
            <button type="button" id="add-more"
                style="background-color: blue; color: white; padding: 10px; margin-top: 10px; border: none; border-radius: 5px;">
                Add More
            </button>

            <!-- Submit Button -->
            <input type="submit"
                style="background-color: green; color: white; padding: 10px; margin-top: 20px; border: none; border-radius: 5px;"
                value="Distribute Bulk">
        </form>

    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('add-more').addEventListener('click', function() {
            // Get the container
            const container = document.getElementById('bulk-distribution-container');

            // Clone the first row
            const firstRow = container.querySelector('.bulk-distribution-row');
            const newRow = firstRow.cloneNode(true);

            // Clear the values in the cloned row
            newRow.querySelectorAll('input, select').forEach(input => input.value = '');

            // Append the cloned row to the container
            container.appendChild(newRow);
        });

        // Event delegation for removing rows
        document.getElementById('bulk-distribution-container').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-row')) {
                const row = e.target.closest('.bulk-distribution-row');
                if (row) {
                    row.remove();
                }
            }
        });
    </script>

</x-app-layout>
