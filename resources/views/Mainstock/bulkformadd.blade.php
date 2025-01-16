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
                        <form method="POST" action="{{ route('bulkadd') }}" enctype="multipart/form-data">
                            @csrf
                            <!-- Dynamic Inputs for Drugs and Quantities -->
                            <div id="bulk-distribution-container">
                                <!-- Template for Inputs -->
                                <div class="bulk-distribution-row" style="display: flex; margin-bottom: 10px;">
                                    <!-- Drug Dropdown -->
                                    <div style="flex: 2; margin-right: 10px;">
                                        <label for="drug_name[]">Drug Name</label><br>
                                        <select name="drug_name[]" class="drug-dropdown" style="width: 100%;" required>
                                            <option value="" disabled selected>Select a drug</option>
                                            <?php $drugs = DB::table('stock_items')->get(); ?>
                                            @foreach ($drugs as $drug)
                                                <option value="{{ $drug->item_number }}"
                                                    data-item-number="{{ $drug->item_number }}">
                                                    {{ $drug->item_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- UOM Input -->
                                    <div style="flex: 1; margin-right: 10px;">
                                        <label for="uom[]">Unit of Measure</label><br>
                                        <input type="number" name="uom[]" min="1"
                                            placeholder="Enter Unit of Measure" style="width: 100%;" required>
                                    </div>
                                    <!-- price Input -->
                                    <div style="flex: 1; margin-right: 10px;">
                                        <label for="price[]">Price</label></label><br>
                                        <input type="number" name="price[]" min="0" step=".01"
                                            placeholder="Enter Price" style="width: 100%;">
                                    </div>

                                    <!-- Quantity Input -->
                                    <div style="flex: 1; margin-right: 10px;">
                                        <label for="quantity[]">Quantity</label><br>
                                        <input type="number" name="quantity[]" min="1"
                                            placeholder="Enter quantity" style="width: 100%;" required>
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
                                value="Add Bulk">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Arrays to store the selected drug item numbers, item names, and quantities
        let drugNames = [];
        let itemNumbers = [];
        let quantities = [];

        // Handle adding more rows
        document.getElementById('add-more').addEventListener('click', function() {
            const container = document.getElementById('bulk-distribution-container');
            const firstRow = container.querySelector('.bulk-distribution-row');
            const newRow = firstRow.cloneNode(true);

            newRow.querySelectorAll('input, select').forEach(input => input.value = ''); // Clear values

            container.appendChild(newRow);
        });

        // Event delegation for removing rows
        document.getElementById('bulk-distribution-container').addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-row')) {
                const row = e.target.closest('.bulk-distribution-row');
                if (row) {
                    row.remove();
                    updateArrays();
                }
            }
        });

        // Listen for changes in the drug dropdown to update the arrays
        document.querySelectorAll('.drug-dropdown').forEach(dropdown => {
            dropdown.addEventListener('change', function() {
                const selectedOption = dropdown.options[dropdown.selectedIndex];
                const itemNumber = selectedOption.getAttribute('data-item-number'); // Capture item number
                const drugName = selectedOption.textContent; // Capture drug name

                // Get the index of the selected dropdown
                const index = Array.from(dropdown.closest('.bulk-distribution-row').parentElement.children)
                    .indexOf(dropdown.closest('.bulk-distribution-row'));

                // Update the itemNumbers array with item_number and drugNames array with drug_name
                itemNumbers[index] = itemNumber;
                drugNames[index] = drugName;

                updateArrays();
            });
        });

        // Listen for changes in the quantity fields
        document.querySelectorAll('input[name="quantity[]"]').forEach(input => {
            input.addEventListener('input', function() {
                const index = Array.from(input.closest('.bulk-distribution-row').parentElement.children)
                    .indexOf(input.closest('.bulk-distribution-row'));

                // Update the quantities array at the corresponding index
                quantities[index] = input.value;

                updateArrays();
            });
        });

        // Helper function to update the arrays and log them (for debugging)
        function updateArrays() {
            console.log('drugNames:', drugNames); // Drug Names for debugging
            console.log('itemNumbers:', itemNumbers); // Item Numbers for debugging
            console.log('quantities:', quantities); // Quantities for debugging
        }

        // Before submitting the form, ensure that the arrays are sent
        document.querySelector('form').addEventListener('submit', function(e) {
            // Append the arrays as hidden inputs to the form before submitting
            appendHiddenInput('item_number', itemNumbers); // Send item_numbers instead of drug names
            appendHiddenInput('quantity', quantities);
        });

        // Function to append a hidden input field with the array data
        function appendHiddenInput(name, array) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = name + '[]';
            input.value = JSON.stringify(array);
            document.querySelector('form').appendChild(input);
        }
    </script>

</x-app-layout>
