<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Product</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .form-group {
            margin-bottom: 1.5rem;
        }

        .btn-custom {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
        }

        .btn-custom:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Create Product</h1>
        <form action="{{ route('products.store') }}" method="POST">
            @csrf

            <!-- Product Name -->
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <!-- Product Description -->
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea name="description" id="description" class="form-control" rows="4" required></textarea>
            </div>

            <!-- Product Price -->
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" name="price" id="price" class="form-control" required>
            </div>

            <!-- Product Image -->
            <div class="form-group">
                <label for="image">Image (URL):</label>
                <input type="text" name="image" id="image" class="form-control">
            </div>

            <!-- Categories -->
            <div class="form-group">
                <label>Categories:</label><br>
                @foreach ($categories as $category)
                    <div class="form-check">
                        <input type="checkbox" name="category_ids[]" value="{{ $category->id }}" class="form-check-input">
                        <label class="form-check-label">{{ $category->name }}</label>
                    </div>
                @endforeach
            </div>

            <!-- Tags -->
            <div class="form-group" id="tags">
                <label for="tags">Tags:</label>
                <input type="text" name="tag[]" placeholder="Tag 1" class="form-control mb-2">
                <button type="button" class="btn btn-info btn-sm" onclick="addTag()">Add More Tags</button>
            </div>

            <!-- Suppliers -->
            <div class="form-group" id="suppliers">
                <label for="suppliers">Suppliers:</label>
                <input type="text" name="supplier_ids[]" placeholder="Supplier 1" class="form-control mb-2">
                <button type="button" class="btn btn-info btn-sm" onclick="addSupplier()">Add More Suppliers</button>
            </div>

            <!-- Profit Margin -->
            <div class="form-group">
                <label for="profit_margin_type">Profit Margin Type:</label>
                <select name="profit_margin_type" id="profit_margin_type" class="form-control"
                    onchange="calculatePrice()">
                    <option value="percentage">Percentage</option>
                    <option value="amount">Amount</option>
                </select>
            </div>

            <div class="form-group">
                <label for="profit_margin_value">Profit Margin Value:</label>
                <input type="number" name="profit_margin_value" id="profit_margin_value" class="form-control"
                    onchange="calculatePrice()">
            </div>

            <!-- Final Price (Read-Only) -->
            <div class="form-group">
                <label for="final_price">Final Price:</label>
                <input type="text" name="final_price" id="final_price" class="form-control" readonly>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-custom">Create Product</button>
        </form>
    </div>

    <!-- Bootstrap JS & jQuery (for Bootstrap JS components like tooltips, modals) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Add more tag fields dynamically
        function addTag() {
            const container = document.getElementById('tags');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'tag[]';
            input.placeholder = 'Tag';
            input.classList.add('form-control', 'mb-2');
            container.appendChild(input);
        }

        // Add more supplier fields dynamically
        function addSupplier() {
            const container = document.getElementById('suppliers');
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'supplier_ids[]';
            input.placeholder = 'Supplier';
            input.classList.add('form-control', 'mb-2');
            container.appendChild(input);
        }

        // Calculate final price (example logic)
        function calculatePrice() {
            const price = parseFloat(document.getElementById('price').value);
            const profitMarginValue = parseFloat(document.getElementById('profit_margin_value').value);
            const profitMarginType = document.getElementById('profit_margin_type').value;

            let finalPrice = price;

            if (profitMarginType === 'percentage') {
                finalPrice += (finalPrice * profitMarginValue) / 100;
            } else if (profitMarginType === 'amount') {
                finalPrice += profitMarginValue;
            }

            document.getElementById('final_price').value = finalPrice.toFixed(2);
        }
    </script>
</body>

</html>