<?php
if (!defined('ABSPATH'))
    die('No direct access allowed');
//***
extract($options);
?>
<div class="divi-control-section-2">

    <h5><?php echo $title ?></h5>

    <div class="divi-control-container">
        <div class="divi-control">
            <?php
            if (!isset($divi_settings[$key]))
            {
                $divi_settings[$key] = $default;
            }
            //***
            switch ($type)
            {
                case 'textinput':
                    ?>
                    <input type="text" placeholder="<?php echo $placeholder ?>" name="divi_settings[<?php echo $key ?>]" value="<?php echo $divi_settings[$key] ?>" id="<?php echo $key ?>" />
                    <?php
                    break;
                case 'color':
                    ?>
                    <input type="text" placeholder="<?php echo $placeholder ?>" class="divi-color-picker" name="divi_settings[<?php echo $key ?>]" value="<?php echo $divi_settings[$key] ?>" id="<?php echo $key ?>" />
                    <?php
                    break;
                case 'select':
                    ?>
                    <select name="divi_settings[<?php echo $key ?>]" id="<?php echo $key ?>">
                        <?php
                        if (!empty($select_options))
                        {
                            foreach ($select_options as $opt_key => $opt_title)
                            {
                                ?>
                                <option <?php echo selected($divi_settings[$key], $opt_key) ?> value="<?php echo $opt_key ?>"><?php echo $opt_title ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                    <?php
                    break;

                default:
                    break;
            }
            ?>


        </div>
        <div class="divi-description">
            <p class="description"><?php echo $description ?></p>
        </div>
    </div>

</div><!--/ .divi-control-section-->
