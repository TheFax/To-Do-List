<?php
session_start();
require "header.php";
$version = "1.6.3";

/**
 * Trova la data e l'ora di ultima modifica più recenti tra tutti i file .php 
 * presenti nella directory corrente.
 *
 * @return string|false La data e l'ora più recenti (nel formato 'Y-m-d H:i:s') o false 
 * se non vengono trovati file .php o si verifica un errore.
 */
function get_latest_php_file_modification_time()
{
    // Ottiene un elenco di tutti i file .php nella directory corrente
    $php_files = glob("*.php");

    // Verifica se sono stati trovati file
    if ($php_files === false || empty($php_files)) {
        // Ritorna false se non ci sono file .php o in caso di errore di glob
        return false;
    }

    $latest_mtime = 0; // Inizializza il timestamp più recente a 0

    // Scorre l'elenco dei file
    foreach ($php_files as $file) {
        // Ottiene il timestamp dell'ultima modifica del file
        $current_mtime = filemtime($file);

        // Verifica se l'operazione ha avuto successo e se questo timestamp 
        // è più recente di quello che abbiamo finora
        if ($current_mtime !== false && $current_mtime > $latest_mtime) {
            $latest_mtime = $current_mtime; // Aggiorna il timestamp più recente
        }
    }

    // Verifica se abbiamo trovato un timestamp valido
    if ($latest_mtime > 0) {
        // Converte il timestamp UNIX (secondi) nel formato data/ora leggibile
        return date('Y-m-d H:i:s', $latest_mtime);
    } else {
        // Ritorna false se, per qualche motivo, non è stato possibile ottenere 
        // i timestamp di modifica (es. problemi di permessi)
        return false;
    }
}

$latest_edit = get_latest_php_file_modification_time();

?>

<body>
    <div id="data_transit" class="div-fixed">
        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
            <g id="SVGRepo_iconCarrier">
                <path d="M13 11L10 16H15L12 21M6 16.4438C4.22194 15.5683 3 13.7502 3 11.6493C3 9.20008 4.8 6.9375 7.5 6.5C8.34694 4.48637 10.3514 3 12.6893 3C15.684 3 18.1317 5.32251 18.3 8.25C19.8893 8.94488 21 10.6503 21 12.4969C21 14.0582 20.206 15.4339 19 16.2417" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
            </g>
        </svg>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-9 m-auto">
                <h2 class="display-4 mx-auto mt-2 text-center">To-do</h2>
                <form id="addTaskForm" class="mt-4" method="post">
                    <div class="form-group">
                        <div class="form-group row">
                            <label for="select_category" class="col-sm-2 col-form-label">Category</label>
                            <div class="col-sm-10">
                                <table>
                                    <tr>
                                        <td class="align-right">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label" for="inlineRadio1">Urgent and
                                                    important </label>
                                                <input class="form-check-input" type="radio" name="category"
                                                    id="inlineRadio1" value="1-DO">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="category"
                                                    id="inlineRadio3" value="2-PLAN" checked>
                                                <label class="form-check-label" for="inlineRadio3">Important</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="align-right">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label" for="inlineRadio2">Urgent </label>
                                                <input class="form-check-input" type="radio" name="category"
                                                    id="inlineRadio2" value="3-DELEGATE">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="category"
                                                    id="inlineRadio4" value="4-DEFAULT">
                                                <label class="form-check-label" for="inlineRadio4">Not important</label>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="text_title" class="col-sm-2 col-form-label">Title</label>
                            <div class="col-sm-10">
                                <input id="text_title" class="form-control form-control-lg" type="text" name="title"
                                    autocomplete="off" maxlength="50" placeholder="Task title" required autofocus>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="text_description" class="col-sm-2 col-form-label">Description</label>
                            <div class="col-sm-10">
                                <input id="text_description" class="form-control " type="text" name="description"
                                    maxlength="200" autocomplete="off" placeholder="Task description">
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <input class="btn btn-success btn-block" type="submit" name="addtask" value="Add Task">
                    </div>
                </form>

            </div>
        </div>

        <?php
        if (isset($_SESSION['message'])) {
            if ($_SESSION['status'] == 'warning') {
                echo '<div class="alert alert-warning text-dark  mx-auto mt-4" role="alert" style="width:66%;">';
            } else if ($_SESSION['status'] == 'danger') {
                echo '<div class="alert alert-danger text-dark  mx-auto mt-4" role="alert" style="width:66%;">';
            } else if ($_SESSION['status'] == 'success') {
                echo '<div class="alert alert-success text-dark  mx-auto mt-4" role="alert" style="width:66%;">';
            } else {
                echo '<div class="alert alert-info text-dark  mx-auto mt-4" role="alert" style="width:66%;">';
            }
            echo $_SESSION['message'];
            echo '</div>';
            unset($_SESSION['message']);
            unset($_SESSION['status']);
        }
        ?>



        <table id="myTable"
            class="col-sm-12 table table-sm table-borderless table-striped text-center mx-auto mt-3 table-hover">
            <thead class="bg-dark text-white text-center">
                <tr>
                    <th style="width:7%;">ID</th>
                    <th class="text-left">Task</th>
                    <th style="width:10%;">Category</th>
                    <th class="hide-on-mobile" style="width:10%;">Move</th>
                    <th style="width:10%;">Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <div class="container my-5">
            <p class="text-right"><em>To-do, by FAX<br>
                    Version: <?php echo $version; ?><br>
                    Latest edit: <?php echo $latest_edit; ?></em></p>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const tableBody = document.querySelector('#myTable tbody');
                const dataTransitDiv = document.getElementById('data_transit');
                let draggedItem = null; // Stores the row being dragged
                let storedItems = []; // This variable will hold your loaded data

                // Function to show or hide the "data in transit" div at the top right of the screen
                const showDataTransit = (isVisible) => {
                    if (isVisible) {
                        dataTransitDiv.style.opacity = '1';
                    } else {
                        dataTransitDiv.style.opacity = '0';
                    }
                };

                // Function to render the table from the storedItems array
                const renderTable = () => {
                    tableBody.innerHTML = ''; // Clear existing rows

                    if (storedItems.length === 0) {
                        const noTaskRow = document.createElement('tr');
                        noTaskRow.innerHTML = '<td colspan="4" class="text-center">No task</td>';
                        tableBody.appendChild(noTaskRow);
                        return;
                    }

                    storedItems.forEach(row => {
                        const tr = document.createElement('tr');
                        tr.setAttribute('draggable', 'true');
                        tr.innerHTML = `
                            <td>${row.id}</td>
                            
                            <td class="text-left">
                                ${row.task_title}<br>
                                <span><small style="color: #888888;">${row.task_description}</small></span>
                            </td>
                            
                            <td>${row.task_category}</td>

                            <td class="hide-on-mobile">
                                <svg class="move-up" xmlns="http://www.w3.org/2000/svg" width="24" height="24"><path d="M12 2a10 10 0 1 0 10 10A10.011 10.011 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8.009 8.009 0 0 1-8 8z"/><path d="m7.293 13.293 1.414 1.414L12 11.414l3.293 3.293 1.414-1.414L12 8.586l-4.707 4.707z"/></svg>
                                <svg class="move-down" xmlns="http://www.w3.org/2000/svg" width="24" height="24"><path d="M12 2a10 10 0 1 0 10 10A10.011 10.011 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8.009 8.009 0 0 1-8 8z"/><path d="M12 12.586 8.707 9.293l-1.414 1.414L12 15.414l4.707-4.707-1.414-1.414L12 12.586z"/></svg>
                            </td>

                            <td>
                                <div class="btn-group">
                                    <a class="btn btn-sm btn-success" href="item_done.php?id=${row.id}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                        </svg>
                                    </a>
                                    <a class="btn btn-sm btn-warning" href="item_edit.php?id=${row.id}">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                        <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z" />
                                        </svg>
                                    </a>
                                    <a class="btn btn-sm btn-danger" href="#">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                        <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        `;
                        tableBody.appendChild(tr);
                    });
                };

                // Function to read all items from the PHP script (AJAX fetch)
                const fetchItems = async () => {
                    try {
                        showDataTransit(true);
                        const response = await fetch('item_readall.php');
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        const data = await response.json();
                        showDataTransit(false);
                        storedItems = data; // Store the fetched data
                        renderTable(); // Render the table with the fetched data
                    } catch (error) {
                        console.error('Error fetching items:', error);
                        tableBody.innerHTML = '<tr><td colspan="4" class="text-center text-danger">Error loading tasks.</td></tr>';
                    }
                };

                // Function used in debug: mostra il nuovo ordine delle righe che sta per essere inviato al server
                /*
                const logRowOrder = () => {
                    console.log("--- Current row order ---");
                    Array.from(tableBody.children).forEach((row, index) => {
                        const rowId = row.querySelector('td:first-child').textContent;
                        console.log(`Index: ${index}, Row ID: ${rowId}`);
                    });
                    console.log("---------------------------------");
                };
                */

                // Event listener for dragstart: store the dragged row and add a class for styling
                tableBody.addEventListener('dragstart', (e) => {
                    if (e.target.tagName === 'TR') {
                        draggedItem = e.target;
                        setTimeout(() => {
                            draggedItem.classList.add('dragging');
                        }, 0);
                    }
                });

                // Event listener for dragend: remove dragging class and update storedItems order
                tableBody.addEventListener('dragend', () => {
                    if (draggedItem) {
                        draggedItem.classList.remove('dragging');
                        draggedItem = null;
                        // After dropping, update the storedItems array to reflect the new order
                        // This is crucial for maintaining the order if you re-render or send to server
                        storedItems = Array.from(tableBody.children).map(row => {
                            const id = row.querySelector('td:first-child').textContent;
                            // Find the original item in storedItems by ID
                            return storedItems.find(item => item.id == id);
                        });
                        //logRowOrder();
                    }
                });

                // Event listener for dragenter: highlight the row where the dragged item will be dropped
                tableBody.addEventListener('dragenter', (e) => {
                    e.preventDefault();
                    if (e.target.tagName === 'TD' && draggedItem !== e.target.parentNode) {
                        Array.from(tableBody.children).forEach(row => {
                            row.classList.remove('drag-over', 'drag-over-first');
                        });

                        const targetRow = e.target.parentNode;
                        if (targetRow.tagName === 'TR' && targetRow !== draggedItem) {
                            const rect = targetRow.getBoundingClientRect();
                            const offsetY = e.clientY - rect.top;

                            // Add a different class depending on whether the drop will be above or below the midpoint
                            if (offsetY < rect.height / 2) {
                                targetRow.classList.add('drag-over'); // Linea sopra
                                targetRow.classList.remove('drag-over-first');
                            } else {
                                targetRow.classList.add('drag-over-first'); // Linea sotto
                                targetRow.classList.remove('drag-over');
                            }
                        }
                    }
                });

                // Event listener for dragover: required to allow dropping
                tableBody.addEventListener('dragover', (e) => {
                    e.preventDefault();
                });

                // Event listener for dragleave: remove highlight from row
                tableBody.addEventListener('dragleave', (e) => {
                    if (e.target.tagName === 'TD') {
                        const targetRow = e.target.parentNode;
                        if (targetRow.tagName === 'TR') {
                            targetRow.classList.remove('drag-over', 'drag-over-first');
                        }
                    }
                });

                // Event listener for drop: move the dragged row to the new position and update order
                tableBody.addEventListener('drop', (e) => {
                    e.preventDefault();
                    if (e.target.tagName === 'TD' && draggedItem) {
                        const targetRow = e.target.parentNode;

                        // Remove all highlight classes
                        Array.from(tableBody.children).forEach(row => {
                            row.classList.remove('drag-over', 'drag-over-first');
                        });

                        if (targetRow.tagName === 'TR' && targetRow !== draggedItem) {
                            const rect = targetRow.getBoundingClientRect();
                            const offsetY = e.clientY - rect.top;

                            // Insert before or after depending on mouse position
                            if (offsetY < rect.height / 2) {
                                tableBody.insertBefore(draggedItem, targetRow);
                            } else {
                                if (targetRow.nextSibling) {
                                    tableBody.insertBefore(draggedItem, targetRow.nextSibling);
                                } else {
                                    tableBody.appendChild(draggedItem);
                                }
                            }
                        }
                    }
                    draggedItem = null;

                    // Update storedItems array after a drop to reflect new DOM order
                    storedItems = Array.from(tableBody.children).map(row => {
                        const id = row.querySelector('td:first-child').textContent;
                        return storedItems.find(item => item.id == id);
                    });
                    //logRowOrder();

                    sendUpdatedPositions(); // Notify server of new order
                });

                // Function to move a row up by one position in the DOM and update order
                const moveRowUp = (rowToMove) => {
                    const previousRow = rowToMove.previousElementSibling;
                    if (previousRow) {
                        tableBody.insertBefore(rowToMove, previousRow);
                        updateStoredItemsOrder(); // Update the stored array
                    }
                };

                // Function to move a row down by one position in the DOM and update order
                const moveRowDown = (rowToMove) => {
                    const nextRow = rowToMove.nextElementSibling;
                    if (nextRow) {
                        tableBody.insertBefore(nextRow, rowToMove);
                        updateStoredItemsOrder(); // Update the stored array
                    }
                };

                // Helper function to update the storedItems array based on current DOM order
                const updateStoredItemsOrder = () => {
                    storedItems = Array.from(tableBody.children).map(row => {
                        const id = row.querySelector('td:first-child').textContent;
                        return storedItems.find(item => item.id == id);
                    });
                };

                // Event Listener for "Up" and "Down" SVG clicks (row reordering)
                tableBody.addEventListener('click', (e) => {
                    // Find the closest ancestor that has either 'move-up' or 'move-down' class
                    const moveButton = e.target.closest('.move-up, .move-down');

                    if (moveButton) { // Check if an ancestor has been found
                        const rowToMove = moveButton.closest('tr'); // Get the parent row of the clicked button

                        if (moveButton.classList.contains('move-up')) {
                            moveRowUp(rowToMove);
                        } else if (moveButton.classList.contains('move-down')) {
                            moveRowDown(rowToMove);
                        }
                        //logRowOrder();
                        sendUpdatedPositions(); // Notify server of new order
                    }

                    // Handle delete button click
                    const deleteBtn = e.target.closest('.btn-danger');
                    if (deleteBtn) {
                        e.preventDefault();
                        const row = deleteBtn.closest('tr');
                        const id = row.querySelector('td:first-child').textContent;
                        handleDelete(id);
                        return;
                    }
                });

                // Function to handle delete action (AJAX call to server)
                const handleDelete = async (id) => {
                    if (!confirm('Are you sure you want to delete this note?')) return;
                    showDataTransit(true);
                    try {
                        const formData = new FormData();
                        formData.append('id', id);
                        const response = await fetch('item_delete.php', {
                            method: 'POST',
                            body: formData
                        });
                        let result;
                        try {
                            result = await response.json();
                        } catch (e) {
                            throw new Error('Invalid response from server.');
                        }
                        if (result.status === 'success') {
                            await fetchItems();
                            // showSuccessAlert se vuoi...
                        } else {
                            alert(result.message || 'Error deleting note.');
                        }
                    } catch (error) {
                        alert('Error during deletion: ' + error.message);
                    } finally {
                        showDataTransit(false);
                    }
                };

                // Function to send the updated row positions to the server (AJAX)
                const sendUpdatedPositions = async () => {
                    const updatedOrder = Array.from(tableBody.children).map(row => {
                        return row.querySelector('td:first-child').textContent; // Get the ID from the first cell
                    });

                    //console.log('Sending updated positions:', updatedOrder); // Debug log
                    try {
                        console.log('Sending position update to server...');
                        showDataTransit(true);
                        const response = await fetch('item_updatepositions.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                item_ids: updatedOrder
                            })
                        });

                        // If the response is not OK, try to read the text to see the PHP error
                        if (!response.ok) {
                            const errorText = await response.text();
                            console.error('Non-JSON response or HTTP error:', errorText);
                            throw new Error(`HTTP server error! Status: ${response.status}. Message: ${errorText.substring(0, 100)}...`);
                        }

                        const result = await response.json();
                        //console.log('Server response:', result);

                        if (result.status === 'success') {
                            console.log('Positions updated successfully!');

                        } else {
                            console.error('Error updating positions:', result.message);
                        }
                        showDataTransit(false);

                    } catch (error) {
                        console.error('Error sending updated positions:', error);
                    }
                };

                // Asynchronous submission of the add task form (AJAX)
                const addTaskForm = document.getElementById('addTaskForm');
                if (addTaskForm) {
                    addTaskForm.addEventListener('submit', async (event) => {
                        event.preventDefault(); // Prevent page reload
                        showDataTransit(true);
                        // Prepare form data
                        const formData = new FormData(addTaskForm);
                        try {
                            const response = await fetch('item_add.php', {
                                method: 'POST',
                                body: formData
                            });
                            // Try to read the response as JSON
                            let result;
                            try {
                                result = await response.json();
                            } catch (e) {
                                // If not JSON, show generic error
                                throw new Error('Invalid response from server.');
                            }
                            if (result.status === 'success') {
                                // Update the table
                                await fetchItems();
                                // Reset the form
                                addTaskForm.reset();
                                // Set the default category (if needed)
                                const defaultRadio = addTaskForm.querySelector('input[name="category"][value="2-PLAN"]');
                                if (defaultRadio) defaultRadio.checked = true;
                                // showSuccessAlert se vuoi....
                            } else {
                                // Show error (customize as needed)
                                alert(result.message || 'Error adding task.');
                            }
                        } catch (error) {
                            alert('Error sending task: ' + error.message);
                        } finally {
                            showDataTransit(false);
                        }
                    });
                }

                // Initial fetch and render when the page loads
                fetchItems();
            });
        </script>
</body>

</html>