<?php


use LDAP\Result;

session_start();

class Connection
{
  public $host = "localhost",
    $user = "root",
    $password = "",
    $db_name = "library_management",
    $conn;

  public function __construct()
  {
    $this->conn = mysqli_connect($this->host, $this->user, $this->password, $this->db_name);
  }
}

class AddToDB extends CheckLogin
{
  public function addMember($nama, $email, $phoneNumber, $address)
  {
    $duplicate = mysqli_query($this->conn, "SELECT * FROM tb_siswa WHERE nama = '$nama' and phoneNumber = '$phoneNumber' ");
    $duplicateEmail = mysqli_query($this->conn, "SELECT * FROM tb_siswa WHERE email = '$email'");

    if (mysqli_num_rows($duplicate) > 0) {
      return 10;
      // Username has already taken
    } elseif (mysqli_num_rows($duplicateEmail) > 0) {
      return 100;
      // Email has already taken
    } else {
      $query = "INSERT INTO tb_siswa VALUES('', '$nama', '$email', '$phoneNumber', '$address' )";
      mysqli_query($this->conn, $query);
      return 1;
      // Registration successful
    }
  }

  public function addBuku($isbn, $nama, $pengarang, $penerbit, $lokasiBuku, $stock)
  {
    $duplicate = mysqli_query($this->conn, "SELECT * FROM tb_buku WHERE nama = '$nama' and pengarang = '$pengarang' ");
    $duplicateISBN = mysqli_query($this->conn, "SELECT * FROM tb_buku WHERE isbn = '$isbn'");

    if (mysqli_num_rows($duplicate) > 0) {
      return 10;
      // buku sudah ada
    } elseif (mysqli_num_rows($duplicateISBN) > 0) {
      return 100;
      // ISBN yg anda masukin sudah ada
    } else {
      $query = "INSERT INTO tb_buku VALUES('', '$isbn','$nama', '$pengarang', '$penerbit', '$lokasiBuku', '$stock')";
      mysqli_query($this->conn, $query);
      return 1;
      // Berhasil tambah buku
    }
  }

  public function CheckSukses($result, $pesansuccess, $pesan1, $pesan2, $pesan3)
  {
    if ($result == 1) {
      echo
      "<script> alert('$pesansuccess'); </script>";
    } elseif ($result == 10) {
      echo
      "<script> alert('$pesan1'); </script>";
    } elseif ($result == 100) {
      echo
      "<script> alert('$pesan2'); </script>";
    } elseif ($result == 1000) {
      echo
      "<script> alert('$pesan3');</script>";
    }
  }

  public function addPeminjam($nama_Peminjam, $phoneNumber, $nama_buku, $date, $keterangan_peminjam, $status = 'belum')
  {
    $cekBuku = mysqli_query($this->conn, "SELECT * FROM tb_buku WHERE nama = '$nama_buku' ");
    $cekNamaPeminjam = mysqli_query($this->conn, "SELECT * FROM tb_siswa WHERE nama = '$nama_Peminjam' and phoneNumber = '$phoneNumber'");
    $cekStock = mysqli_query($this->conn, "SELECT stock FROM tb_buku WHERE nama = '$nama_buku'");
    if (mysqli_num_rows($cekBuku) > 0) {
      if (mysqli_num_rows($cekNamaPeminjam) > 0) {
        $intstock = implode(mysqli_fetch_array($cekStock));
        if ($intstock > 1) {
          $query = "INSERT INTO tb_peminjam VALUES('', '$nama_Peminjam','$phoneNumber','$nama_buku', CAST('$date' as DATE),'$keterangan_peminjam', '$status')";
          mysqli_query($this->conn, $query);
          mysqli_query($this->conn, "UPDATE tb_buku SET stock = stock - 1  WHERE nama='$nama_buku'");
          return 1;
          // Berhasil dipinjamkan
        } else {
          return 1000;
        }
      } else {
        return 100;
        // Nama peminjam tidak terdaftar
      }
    } else {
      return 10;
      // buku tidak ada
    }
  }

  public function Delete($tbName, $id)
  {
    mysqli_query($this->conn, "delete from $tbName where id='$id'");
  }

  public function DeletePeminjamPlus($tbName, $id, $nama_buku)
  {
    mysqli_query($this->conn, "delete from $tbName where id='$id'");
    mysqli_query($this->conn, "UPDATE tb_buku SET stock = stock + 1  WHERE nama='$nama_buku'");
  }

  public function DeletePeminjamMines($tbName, $id)
  {
    mysqli_query($this->conn, "delete from $tbName where id='$id'");
  }
  public function MauKembalikanBuku($status, $id, $nama_buku)
  {
    mysqli_query($this->conn, "UPDATE tb_peminjam SET status = '$status' WHERE id='$id'");
    mysqli_query($this->conn, "UPDATE tb_buku SET stock = stock + 1  WHERE nama='$nama_buku'");
  }

  public function BelumKembalikanBuku($status, $id, $nama_buku)
  {
    mysqli_query($this->conn, "UPDATE tb_peminjam SET status = '$status' WHERE id='$id'");
    mysqli_query($this->conn, "UPDATE tb_buku SET stock = stock - 1  WHERE nama='$nama_buku'");
  }

  public function UpdateBuku($id, $isbn, $nama, $pengarang, $penerbit, $lokasiBuku, $stock, $namaBuku)
  {
    mysqli_begin_transaction($this->conn);

    $queryPeminjam = "UPDATE tb_peminjam SET nama_buku = '$nama' WHERE nama_buku = '$namaBuku'";
    mysqli_query($this->conn, $queryPeminjam);
    $query = "UPDATE tb_buku SET isbn='$isbn',nama='$nama',pengarang='$pengarang',penerbit='$penerbit',lokasiBuku='$lokasiBuku',stock='$stock' WHERE id='$id'";
    mysqli_query($this->conn, $query);
    $query1 = mysqli_query($this->conn, "SELECT * FROM tb_buku WHERE isbn = '$isbn' ");
    $query2 = mysqli_query($this->conn, "SELECT * FROM tb_buku WHERE nama = '$nama' and pengarang = '$pengarang' ");
    if (mysqli_num_rows($query1) > 1) {
      mysqli_rollback($this->conn);
      return 10;
    } else if (mysqli_num_rows($query2) > 1) {
      mysqli_rollback($this->conn);
      return 100;
    } else {

      mysqli_commit($this->conn);
      return 1;
      // Berhasil update buku
    }
  }

  public function UpdateMember($id, $nama, $email, $phoneNumber, $address)
  {
    mysqli_begin_transaction($this->conn);
    $query = "UPDATE tb_siswa SET nama='$nama',email='$email',phoneNumber='$phoneNumber',address='$address' WHERE id='$id'";
    mysqli_query($this->conn, $query);
    $query1 = mysqli_query($this->conn, "SELECT * FROM tb_siswa WHERE nama = '$nama' and phoneNumber = '$phoneNumber' ");
    $query2 = mysqli_query($this->conn, "SELECT * FROM tb_siswa WHERE email = '$email'");
    if (mysqli_num_rows($query1) > 1) {
      mysqli_rollback($this->conn);
      return 10;
    } else if (mysqli_num_rows($query2) > 1) {
      mysqli_rollback($this->conn);
      return 100;
    } else {
      mysqli_commit($this->conn);
      return 1;
    }
  }
}

class Login extends Connection
{
  public $id;

  public function login($username, $password)
  {
    $result = mysqli_query($this->conn, "SELECT * FROM adminPerpus WHERE username = '$username'");
    $row = mysqli_fetch_assoc($result);

    if (mysqli_num_rows($result) > 0) {
      if ($password == $row["password"]) {
        $this->id = $row["id"];
        return 1;
        // Login successful
      } else {
        return 10;
        // Wrong password
      }
    } else {
      return 100;
      // User not registered
    }
  }

  public function CheckUserPass($result, $login)
  {
    if ($result == 1) {
      $_SESSION["login"] = true;
      $_SESSION["id"] = $login->idUser();
      header("Location: dashboard.php");
    } elseif ($result == 10) {
      echo
      "<script> alert('Wrong Password'); </script>";
    } elseif ($result == 100) {
      echo
      "<script> alert('User Not Registered'); </script>";
    }
  }

  public function idUser()
  {
    return $this->id;
  }
}


class CheckLogin extends Connection
{
  protected function selectUserById($id)
  {
    $result = mysqli_query($this->conn, "SELECT * FROM adminperpus WHERE id = $id");
    return mysqli_fetch_assoc($result);
  }

  public function CheckLoginIndex()
  {
    if (!empty($_SESSION["id"])) {
      header("Location: dashboard.php");
    }
  }

  public function CheckLoginSession()
  {
    if (!empty($_SESSION["id"])) {
      return $this->selectUserById($_SESSION["id"]);
    } else {
      header("Location: index.php");
    }
  }

  public function CallName($tableName)
  {
    $user = $this->CheckLoginSession();
    echo $user["$tableName"];
  }
}

class Dashboard extends CheckLogin
{
  public function ViewAngka($namaColumn, $namaTable)
  {
    $result = mysqli_query($this->conn, "SELECT $namaColumn  from $namaTable");
    return mysqli_num_rows($result);
  }

  public function ViewAngkaWhere($namaColumn, $namaTable, $valueRow)
  {
    $result = mysqli_query($this->conn, "SELECT $namaColumn from $namaTable WHERE status = '$valueRow'");
    return mysqli_num_rows($result);
  }
}

class TableList extends AddToDB
{
  public function List($tableName)
  {
    $array = array();
    $sql = "select * from " . $tableName . "";
    $result = mysqli_query($this->conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
      $array[] = $row;
    }
    return $array;
  }

  public function ListOrder($tableName, $tableColumn, $ASCorDESC)
  {
    $array = array();
    $sql = "select * from " . $tableName . " ORDER BY " . $tableColumn . " " . $ASCorDESC . "";
    $result = mysqli_query($this->conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
      $array[] = $row;
    }
    return $array;
  }

  public function ListLimited($tableName, $number)
  {
    $array = array();
    $sql = "select * from " . $tableName . " ORDER BY id DESC LIMIT " . $number . "";
    $result = mysqli_query($this->conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
      $array[] = $row;
    }
    return $array;
  }

  public function ListSearch($tableName, $searchName, $searchRow, $searchRow2)
  {
    $array = array();
    $sql = "select * from " . $tableName . " WHERE $searchRow = '$searchName' or $searchRow2 = '$searchName'";
    $result = mysqli_query($this->conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
      $array[] = $row;
    }
    return $array;
  }

  public function ListSearchPeminjam($tableName, $searchName, $searchRow, $searchRow2, $searchRow3, $searchRow4)
  {
    $array = array();
    $sql = "select * from " . $tableName . " WHERE $searchRow = '$searchName' or $searchRow2 = '$searchName' or $searchRow3 = '$searchName' or $searchRow4 = '$searchName'";
    $result = mysqli_query($this->conn, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
      $array[] = $row;
    }
    return $array;
  }
}
