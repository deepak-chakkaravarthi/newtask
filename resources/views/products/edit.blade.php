<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }
        .container {
            width: 60%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            border-radius: 8px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        input[type="text"], input[type="number"], textarea, select {
            width: 100%;
            padding: 8px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        textarea {
            height: 100px;
        }
        .checkbox-group label {
            display: inline-block;
            margin-right: 15px;
        }
        .checkbox-group input[type="checkbox"] {
            margin-right: 5px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #45a049;
        }
        .error-message {
            color: red;
            background-color: #f8d7da;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        a {
            text-decoration: none;
            color: #007BFF;
            font-size: 14px;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h1>Update Product</h1>

    @if ($errors->any())
        <div class="error-message">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container">
        <form action="{{ route('products.update', $product->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="{{ $product->name }}" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required>{{ $product->description }}</textarea>
            </div>

            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" value="{{ $product->price }}" required>
            </div>

            <div class="form-group checkbox-group">
                <label>Categories:</label><br>
                @foreach ($categories as $category)
                    <input type="checkbox" name="category_ids[]" value="{{ $category->id }}"
                        {{ $product->categories->contains($category->id) ? 'checked' : '' }}>
                    {{ $category->name }}<br>
                @endforeach
            </div>

            <div class="form-group checkbox-group">
                <label>Suppliers:</label><br>
                @foreach ($suppliers as $supplier)
                    <input type="checkbox" name="supplier_ids[]" value="{{ $supplier->id }}"
                        {{ $product->suppliers->contains($supplier->id) ? 'checked' : '' }}>
                    {{ $supplier->name }}<br>
                @endforeach
            </div>

            <div class="form-group">
                <label for="profit_margin_type">Profit Margin Type:</label>
                <select id="profit_margin_type" name="profit_margin_type">
                    <option value="percentage" {{ $product->profit_margin_type === 'percentage' ? 'selected' : '' }}>Percentage</option>
                    <option value="amount" {{ $product->profit_margin_type === 'amount' ? 'selected' : '' }}>Amount</option>
                </select>
            </div>

            <div class="form-group">
                <label for="profit_margin_value">Profit Margin Value:</label>
                <input type="number" id="profit_margin_value" name="profit_margin_value" value="{{ $product->profit_margin_value }}">
            </div>

            <button type="submit">Update Product</button>
        </form>

        <br>
        <a href="{{ route('products.list') }}">Back to Product List</a>
    </div>

</body>
</html>
