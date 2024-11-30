<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

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
            width: 80%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
        }

        .btn {
            padding: 8px 12px;
            margin: 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-view {
            background-color: #007bff;
            color: white;
        }

        .btn-create {
            background-color: #28a745;
            color: white;
        }

        .btn-update {
            background-color: #ffc107;
            color: white;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
        }

        .btn:hover {
            opacity: 0.8;
        }

        .btn-logout {
            background-color: #6c757d;
            color: white;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .tags,
        .suppliers {
            display: flex;
            gap: 10px;
        }

        .tags span,
        .suppliers span {
            background-color: #f8f9fa;
            padding: 5px 10px;
            border-radius: 20px;
            border: 1px solid #ddd;
        }

        .final-amount,
        .profit-margin {
            font-weight: bold;
            color: #333;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            table {
                width: 100%;
                font-size: 14px;
                overflow-x: auto;
            }

            .container {
                width: 90%;
            }

            .actions {
                flex-direction: column;
                align-items: flex-start;
            }

            .tags,
            .suppliers {
                flex-wrap: wrap;
            }
        }
    </style>
</head>

<body>

    <h1>Product List</h1>

    <div class="container">
        <!-- Logout Button -->
        <form action="{{ route('logout') }}" method="POST" class="logout-form">
            @csrf
            <button type="submit" class="btn btn-logout">Logout</button>
        </form>

        <!-- Create Product Button (Admin Only) -->
        @if ($isAdmin)
            <a href="{{ route('products.create') }}" class="btn btn-create">Create Product</a>
        @endif

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Tags</th>
                    <th>Suppliers</th>
                    <th>Profit Margin</th>
                    <th>Final Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->price }}</td>
                        <td>
                            <div class="tags">
                                @foreach($product->tags as $productTag)
                                    <span>{{ $productTag->tag }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <div class="suppliers">
                                @foreach ($product->suppliers as $supplier)
                                    <span>{{ $supplier->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="profit-margin">
                            {{ $product->profit_margin_type }} - {{ $product->profit_margin_value }}
                        </td>
                        <td class="final-amount">{{ $product->final_price }}</td>
                        <td class="actions">
                            <a href="{{ route('products.details', $product->id) }}" class="btn btn-view"
                                title="View Product Details">View</a>

                            <!-- Admin Actions -->
                            @if ($isAdmin)
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-update"
                                    title="Edit Product">Edit</a>

                                <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                    style="display: inline;"
                                    onsubmit="return confirm('Are you sure you want to delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete" title="Delete Product">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>

</html>