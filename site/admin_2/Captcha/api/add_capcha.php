<?php
if (isset($_POST['submit'])) {

    $img = $_FILES['image']['name'];
    $img_tmp = $_FILES['image']['tmp_name'];
    $img_ext = strtolower(pathinfo($img, PATHINFO_EXTENSION));

    $valid_extensions = array('jpg', 'jpeg', 'png');
    if (in_array($img_ext, $valid_extensions)) {
        $existing_folders = glob("image/capcha*");
        $highest_index = 0;
        foreach ($existing_folders as $folder) {
            $index = intval(str_replace("image/capcha", "", $folder));
            if ($index > $highest_index) {
                $highest_index = $index;
            }
        }
        $tile_folder_name = 'capcha' . ($highest_index + 1);
        mkdir("image/$tile_folder_name");

        $tiles_per_row = 3;
        $tiles_per_col = 3;

        $img_resource = imagecreatefromstring(file_get_contents($img_tmp));
        $img_width = imagesx($img_resource);
        $img_height = imagesy($img_resource);

        $tile_width = intval(floor($img_width / $tiles_per_row));
        $tile_height = intval(floor($img_height / $tiles_per_col));

        $i = 1;
        for ($row = 0; $row < $tiles_per_col; $row++) {
            for ($col = 0; $col < $tiles_per_row; $col++) {

                $tile_img = imagecreatetruecolor($tile_width, $tile_height);

                imagecopyresampled($tile_img, $img_resource, 0, 0,
                    $col * $tile_width, $row * $tile_height,
                    $tile_width, $tile_height, $tile_width, $tile_height);

                $tile_name = $i . '.jpg';
                $tile_path = 'image/' . $tile_folder_name . '/' . $tile_name;

                imagejpeg($tile_img, $tile_path, 100);

                imagedestroy($tile_img);
                $i++;
            }
        }

        $full_img_name = '10.jpg';
        $full_img_path = 'image/' . $tile_folder_name . '/' . $full_img_name;
        move_uploaded_file($img_tmp, $full_img_path);

        imagedestroy($img_resource);

        echo "L'image a été uploadée et découpée avec succès !";

        header('location:list_capcha.php');

    } else {
        echo "L'image doit être au format jpg, jpeg ou png.";
    }
}
?>
