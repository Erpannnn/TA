<?php
session_start();
require 'dbcon.php';

// Input field validation
function validate($inputData) {
    global $conn;
    $validatedData = mysqli_real_escape_string($conn, $inputData);
    return trim($validatedData);
}

// Redirect from one page to another page with the message (status)
function redirect($url, $status) {
    
    $_SESSION['status'] = $status;
    header('Location: ' . $url);
    exit(0);
     
}

// Display Massage
function alertMessage() {

    if (isset($_SESSION['status'])) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">  
        <h6> '.$_SESSION['status'].' </h6>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
        unset($_SESSION['status']);
    }
}

 // Add Data
function insert($tableName, $data) {
    
    global $conn;
    
    $table = validate($tableName);
    
    $columns = array_keys($data);
    $values = array_values($data);
    
    $finalColumns = implode(", ", $columns);
    $finalValues = "'" .implode("', '", $values). "'";
    
    $query = "INSERT INTO $table ($finalColumns) VALUES ($finalValues)";
    $result = mysqli_query($conn, $query);
    return $result;
}

// Update
function update($tableName, $id, $data) {
    
    global $conn;
    
    $table = validate($tableName);
    $id = validate($id);
    
    $updateDataString = '';
    
    foreach ($data as $column => $value) {
        $updateDataString .= $column. '='." '($value)',";
    }

    $finalUpdateData = substr(trim($updateDataString),0,1 );

    $query = "UPDATE $table SET $finalUpdateData WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    return $result;
}

function getAll($tableName, $status) {
    
    global $conn;
    
    $table = validate($tableName);
    $status = validate($status);

    if ($status == 'status') 
    {
        $query = "SELECT * FROM $table WHERE status='O'";
    }
    else
    {
        $query = "SELECT * FROM $table";
    }
    return mysqli_query($conn, $query);
}

function getById($tableName, $id) {
    global $conn;
    
    $table = validate($tableName);
    $id = validate($id);
    
    $query = "SELECT * FROM $table WHERE id='$id' LIMIT 1 ";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $response = [
                'status' => 404,
                'data' => $row,
                'message' => 'Ketemu!'
            ];
        } else {
            $response = [
                'status' => 404,
                'message' => 'Data tidak ketemu'
            ];
        }
    } else {
        $response = [
            'status' => 500,
            'message' => 'Error!'
        ];
    }
    
    return $response;
}
// Delete Data
function delete($tableName, $id) {
    
    global $conn;

    $table = validate($tableName);
    $id = validate($id);
    
    $query = "DELETE FROM $table WHERE id='$id' LIMIT 1";
    $result = mysqli_query($conn, $query);
    return $result;
}






