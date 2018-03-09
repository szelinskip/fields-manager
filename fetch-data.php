<?php
    session_start();
    if(!isset($_SESSION['logged']))
    {
        header('Location: index.php');
        exit();
    }
    require_once "connect.php";
    $connection = @new mysqli($host, $db_user, $db_password, $db_name);
    if($connection->errno!=0)
    {
        echo '<span style = "color:red">Błąd serwera!</span>';
        exit();
    }
    $connection->set_charset("utf8");

    $id_user = $_SESSION['id_user'];
    $sql_query = "SELECT id_place, place_name FROM places";
    // fetch data about all available places
    $places_array = array();
    if($result = $connection->query($sql_query))
    {
        while($row = $result->fetch_assoc())
        {
            $id_place = $row['id_place'];
            $place_name = $row['place_name'];
            $places_array[] = array("id_place"=>$id_place, "place_name"=>$place_name);
        }
        $result->free_result();
    }
    else
    {
        $connection->close();
        echo '<span style = "color:red">Błąd serwera!</span>';
        exit();
    }
    $sql_query = "SELECT id_plant, plant_name FROM plants";
    // fetch data about all available plants
    $plants_array = array();
    if($result = $connection->query($sql_query))
    {
        while($row = $result->fetch_assoc())
        {
            $id_plant = $row['id_plant'];
            $plant_name = $row['plant_name'];
            $plants_array[] = array("id_plant"=>$id_plant, "plant_name"=>$plant_name);
        }
        $result->free_result();
    }
    else
    {
        $connection->close();
        echo '<span style = "color:red">Błąd serwera!</span>';
        exit();
    }
    $sql_query = "SELECT id_color, color_hex_code FROM colors";
    // fetch data about all available colors
    $colors_array = array();
    if($result = $connection->query($sql_query))
    {
        while($row = $result->fetch_assoc())
        {
            $id_color = $row['id_color'];
            $color_hex_code = $row['color_hex_code'];
            $colors_array[] = array("id_color"=>$id_color, "color_hex_code"=>$color_hex_code);
        }
        $result->free_result();
    }
    else
    {
        $connection->close();
        echo '<span style = "color:red">Błąd serwera!</span>';
        exit();
    }
    $sql_query = "SELECT id_field, id_place, area, id_color, description FROM fields WHERE id_user = '$id_user'";
    // fetch data about all available
    $fields_array = array();
    if($result = $connection->query($sql_query))
    {
        while($row = $result->fetch_assoc())
        {
            $id_field = $row['id_field'];
            $id_place = $row['id_place'];
            $area = $row['area'];
            $id_color = $row['id_color'];
            $description = $row['description'];
            $fields_array[] = array("id_field"=>$id_field, "id_place"=>$id_place, "area"=>$area, "id_color"=>$id_color, "description"=>$description);
        }
        $result->free_result();
    }
    else
    {
        $connection->close();
        echo '<span style = "color:red">Błąd serwera!</span>';
        exit();
    }
    // fetch data about fields' coordinates
    $coor_array = array();
    $counter  = 0;
    foreach($fields_array as $field)
    {
        $id_field = $field['id_field'];
        $sql_query = "SELECT coordinates.id_coor, coordinates.lat, coordinates.lng FROM coordinates INNER JOIN located on coordinates.id_coor = located.id_coor WHERE located.id_field ='$id_field' ORDER BY located.number ASC";
        $coor_array[] = array("id_field"=>$id_field);
        if($result = $connection->query($sql_query))
        {
            while($row = $result->fetch_assoc())
            {
                $coor_array[$counter][] = ["coordinate"=>$row];
            }
            $result->free_result();
        }
        else
        {
            $connection->close();
            echo '<span style = "color:red">Błąd serwera!</span>';
            exit();
        }
        $counter++;
    }
    //$sql_query = "SELECT id_plant FROM planted WHERE id_field = 1";
    //todo finish fetching data

    $connection->close();
?>