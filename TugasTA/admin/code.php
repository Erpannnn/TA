<?php
include('../config/function.php');

if (isset($_POST['saveAdmin'])) {
    $nama = validate($_POST['nama']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $phone = validate($_POST['no_telp']);
    $is_ban = isset($_POST['is_ban']) == true ? 1 : 0;

    if ($nama != '' && $email != '' && $password != '') {
        
        $emailCheck = mysqli_query($conn, "SELECT * FROM admin WHERE email='$email'");
        if ($emailCheck) {
            if (mysqli_num_rows($emailCheck) > 0) {
                redirect('tambahadmin.php', 'Email Sudah DiPakai!');
            }
        } 

        $bcrypt_password = password_hash($password, PASSWORD_BCRYPT);

        
        $data = [
            'nama' => $nama,
            'email' => $email,
            'password' => $bcrypt_password,
            'no_telp' => $no_telp,
            'is_ban' => $is_ban
        ];

        
        $result = insert('admin', $data);
        if ($result) {
            redirect('admin.php', 'Akun Berhasil Dibuat!');
        } else {
            redirect('tambahadmin.php', 'Akun Gagal Dibuat!');
        }
    } else {
        redirect('tambahadmin.php', 'Tolong Isi Yang Kosong!');
    }
}
?>
