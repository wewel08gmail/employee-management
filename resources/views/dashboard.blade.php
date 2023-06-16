<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        
        h1 {
            color: #333;
        }
        h2{
            text-align: center;
        }
        form {
            margin-top: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {            
            padding: 10px 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"]{
            width:280px;
        }
        select {
            width: 300px;
        }
        button,
        button[type="submit"] {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover,
        button[type="submit"]:hover {
            background-color: #555;
        }
        button.edit-button{            
            margin-right:20px;
        }
        .form-container{
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .message {
            margin-bottom: 10px;
            /*border: 1px solid #d6e9c6;*/
            background-color: #dff0d8;
            color: #3c763d;
            
        }
        table{
            margin:0 auto;
        }
        table tbody td{
            padding:20px;
        }
        .update-fields {
            display: none;
        }
        .action-container{
            display:flex;
        }
        .action-container form{
            margin-top:0;
        }
    </style>
</head>
<body>
    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif
    <div class="form-container">
    <h2>Add Employee</h2>
    <form method="POST" action="{{ url('/employees') }}">
        @csrf
        <label for="name">Name:</label>
        <input type="text" name="name" required>
        <br>
        <label for="position">Position:</label>
        <select name="position">
            <option value="Manager">Manager</option>
            <option value="Web Developer">Web Developer</option>
            <option value="Web Designer">Web Designer</option>
        </select>
        <br>
        <button type="submit">Add Employee</button>
    </form>
    </div>
    <h2>Employee List</h2>
    <table>
        <tr>
            <th>Name</th>
            <th>Position</th>
            <th>Action</th>
        </tr>
        @foreach ($employees as $employee)
            <tr data-id="{{ $employee->id }}">
                <td>{{ $employee->name }}</td>
                <td>{{ $employee->position }}</td>
                <td>
                    <div class="action-container">
                        <button class="edit-button" data-id="{{ $employee->id }}">Edit</button>
                        <!--
                        <form method="POST" action="{{ url('/employees/' . $employee->id) }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="_method" value="PUT">
                            <input type="text" name="name" required>
                            @if(auth()->check() && auth()->user()->role === 'Manager')
                                <select name="position">
                                    <option value="Manager">Manager</option>
                                    <option value="Web Developer">Web Developer</option>
                                    <option value="Web Designer">Web Designer</option>
                                </select>
                            @endif
                            <button type="submit">Update</button>
                        </form>
                        -->
                    

                        <form method="POST" action="{{ url('/employees/' . $employee->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            <tr class="update-row" style="display: none;">
                <td colspan="3">
                    <!-- Update form -->
                    <div class="message"></div>
                    <form class="update-form">
                        @csrf
                        <input type="hidden" name="employee_id">
                        <label for="name">Name:</label>
                        <input type="text" name="name">
                        @if(auth()->check() && auth()->user()->role === 'Manager')
                        <br>
                        <label for="position">Position:</label>
                        <select name="position">
                            <option value="Manager">Manager</option>
                            <option value="Web Developer">Web Developer</option>
                            <option value="Web Designer">Web Designer</option>
                        </select>
                        @else
                        <input type="hidden" name="position">   
                        @endif
                        <br>
                        <button type="submit">Update</button>
                        <button type="button" class="cancel-button">Cancel</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>


    <script>        
    document.addEventListener('DOMContentLoaded', function() {

        const editButtons = document.querySelectorAll('.edit-button');
        const updateRows = document.querySelectorAll('.update-row');
        const updateForms = document.querySelectorAll('.update-form');
        const cancelButton = document.querySelectorAll('.cancel-button');


        editButtons.forEach((editButton, index) => {
            editButton.addEventListener('click', () => {
            
                updateRows.forEach(row => (row.style.display = 'none'));
                updateForms.forEach(form => form.reset());
            
                const clickedIndex = Array.from(editButtons).indexOf(editButton);
            
                updateRows[clickedIndex].style.display = 'table-row';
          
                const employeeId = editButton.dataset.id;
                fetch(`/employees/${employeeId}`)
                    .then(response => response.json())
                    .then(data => {
                        updateForms[clickedIndex].elements.employee_id.value = data.id;
                        updateForms[clickedIndex].elements.name.value = data.name;
                        updateForms[clickedIndex].elements.position.value = data.position;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });

            });
        });

        cancelButton.forEach((button, index) => {
            button.addEventListener('click', () => {
                updateRows[index].style.display = 'none';
            });
        });

        


        updateForms.forEach(form => {
            form.addEventListener('submit', (event) => {
                event.preventDefault();

                const formData = new FormData(form);
                const employeeId = formData.get('employee_id');
                const updatedName = formData.get('name');
                const updatedPosition = formData.get('position');

                fetch(`/employees/${employeeId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        name: updatedName,
                        position: updatedPosition
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Employee updated:', data);
                        
                        const messageContainer = form.previousElementSibling;
                        messageContainer.textContent = `Employee updated: ${data.name} - ${data.position}`;
                        messageContainer.style.display = 'block';

                        const tableRow = document.querySelector(`tr[data-id="${data.id}"]`);
                        tableRow.children[0].textContent = data.name;
                        tableRow.children[1].textContent = data.position;

                    })
                    .catch(error => {
                        console.error('Error:', error);
                        
                        const messageContainer = form.previousElementSibling;
                        messageContainer.textContent = 'An error occurred while updating the employee.';
                        messageContainer.style.display = 'block';
                    });
            });
        });



    });
    </script>

</body>
</html>
