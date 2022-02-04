<?php
if ( ! defined('ABSPATH')) exit;  // if direct access 


add_action( 'show_user_profile', 'edit_user_profile' );
add_action( 'edit_user_profile', 'edit_user_profile' );

add_action( 'personal_options_update',  'save_update_user_profile' );
add_action( 'edit_user_profile_update', 'save_update_user_profile' );



function save_update_user_profile($user_id){


    if ( ! current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }

    $option_value = isset($_POST['phone_number']) ? $_POST['phone_number'] : '';


    update_user_meta($user_id, 'phone_number', $option_value);


}


function edit_user_profile($user){

    $user_id = $user->ID;

    $phone_number = get_user_meta($user_id,'phone_number', true);

    //var_dump($phone_number);

            ?>
            <h2><?php echo 'User Verification'; ?></h2>
            <p class="description"><?php echo ''; ?></p>
            <table class="form-table">
                <?php
                    ?>
                    <tr>
                        <th><label for="<?php //echo $option['id']; ?>"><?php echo 'Phone number'; ?></label></th>
                        <td>
                            <input type="number" name="phone_number" id="phone_number" placeholder="" value="<?php echo $phone_number; ?>" class="regular-text">
                        </td>
                    </tr>
                <?php


                ?>
            </table>
        <?php

}
