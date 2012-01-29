<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
    <div class="left"></div>
    <div class="right"></div>
    <div class="heading">
        <h1 style="background-image: url('view/image/payment.png');"><?php echo $heading_title; ?></h1>
        <div class="buttons">
            <a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a>
            <a onclick="location='<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a>
        </div>
    </div>
    <div class="content" style="min-height:730px;">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
            <table class="form">
                <tr>
                    <td><?php echo $entry_status; ?></td>
                    <td><select name="moip_status">
                            <?php if ($moip_status) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                    </select></td>
                </tr>
                <tr>
                    <td width="18%"><span class="required">*</span> <?php echo $entry_email; ?></td>
                    <td width="82%"><input type="text" name="moip_email" value="<?php echo $moip_email; ?>" />
                        <br />
                        <?php if ($error_email) { ?>
                        <span class="error"><?php echo $error_email; ?></span>
                        <?php } ?></td>
                </tr>
                <tr>
                    <td><span class="required">*</span> <?php echo $entry_encryption; ?></td>
                    <td><div class="help"><?php echo $help_encryption; ?></div>
                        <input type="text" name="moip_encryption" value="<?php echo $moip_encryption; ?>" />
                        <br />
                        <?php if ($error_encryption) { ?>
                        <span class="error"><?php echo $error_encryption; ?></span>
                        <?php } ?></td>
                </tr>
                <tr>
                    <td><?php echo $entry_status; ?></td>
                    <td><select name="moip_status">
                            <?php if ($moip_status) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                    </select></td>
                </tr>
                <tr>
                    <td><?php echo $entry_test; ?></td>
                    <td><?php if ($moip_test) { ?>
                        <input type="radio" name="moip_test" value="1" checked="checked" />
                        <?php echo $text_yes; ?>
                        <input type="radio" name="moip_test" value="0" />
                        <?php echo $text_no; ?>
                        <?php } else { ?>
                        <input type="radio" name="moip_test" value="1" />
                        <?php echo $text_yes; ?>
                        <input type="radio" name="moip_test" value="0" checked="checked" />
                        <?php echo $text_no; ?>
                        <?php } ?></td>
                </tr>
                <tr>
                    <td><?php echo $entry_aguardando; ?></td>
                    <td><select name="moip_aguardando" id="moip_aguardando">
                            <?php foreach ($order_statuses as $order_status) { ?>
                            <?php if ($order_status['order_status_id'] == $moip_aguardando) { ?>
                            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected">
                                <?php echo $order_status['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $order_status['order_status_id']; ?>">
                            <?php echo $order_status['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                        <br />
                        <div class="help"><?php echo $help_aguardando; ?></div></td>
                </tr>
                <tr>
                    <td><?php echo $entry_cancelado; ?></td>
                    <td><select name="moip_cancelado" id="moip_cancelado">
                            <?php foreach ($order_statuses as $order_status) { ?>
                            <?php if ($order_status['order_status_id'] == $moip_cancelado) { ?>
                            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected">
                            <?php echo $order_status['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                        <br />
                        <div class="help"><?php echo $help_cancelado; ?></div></td>
                </tr>
                <tr>
                    <td><?php echo $entry_aprovado; ?></td>
                    <td><select name="moip_aprovado" id="moip_aprovado">
                            <?php foreach ($order_statuses as $order_status) { ?>
                            <?php if ($order_status['order_status_id'] == $moip_aprovado) { ?>
                            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected">
                                <?php echo $order_status['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $order_status['order_status_id']; ?>">
                            <?php echo $order_status['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                        <br />
                        <div class="help"><?php echo $help_aprovado; ?></div></td>
                </tr>
                <tr>
                    <td><?php echo $entry_analize; ?></td>
                    <td><select name="moip_analize" id="moip_analize">
                            <?php foreach ($order_statuses as $order_status) { ?>
                            <?php if ($order_status['order_status_id'] == $moip_analize) { ?>
                            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected">
                            <?php echo $order_status['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $order_status['order_status_id']; ?>">
                            <?php echo $order_status['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                        <br />
                        <div class="help"><?php echo $help_analize; ?></div></td>
                </tr>
                <tr>
                    <td><?php echo $entry_completo; ?></td>
                    <td><select name="moip_completo" id="moip_completo">
                            <?php foreach ($order_statuses as $order_status) { ?>
                            <?php if ($order_status['order_status_id'] == $moip_completo) { ?>
                            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected">
                            <?php echo $order_status['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $order_status['order_status_id']; ?>">
                            <?php echo $order_status['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                        </select>
                        <br />
                        <div class="help"><?php echo $help_completo; ?></div></td>
                </tr>
                <tr>
                    <td><?php echo $entry_order_status; ?></td>
                    <td><select name="moip_order_status_id">
                            <?php foreach ($order_statuses as $order_status) { ?>
                            <?php if ($order_status['order_status_id'] == $moip_order_status_id) { ?>
                            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected">
                            <?php echo $order_status['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $order_status['order_status_id']; ?>">
                            <?php echo $order_status['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                    </select></td>
                </tr>
                <tr>
                    <td><?php echo $entry_geo_zone; ?></td>
                    <td><select name="moip_geo_zone_id">
                            <option value="0"><?php echo $text_all_zones; ?></option>
                            <?php foreach ($geo_zones as $geo_zone) { ?>
                            <?php if ($geo_zone['geo_zone_id'] == $moip_geo_zone_id) { ?>
                            <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected">
                            <?php echo $geo_zone['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                    </select></td>
                </tr>
                <tr>
                    <td><?php echo $entry_sort_order; ?></td>
                    <td><input type="text" name="moip_sort_order" value="<?php echo $moip_sort_order; ?>" size="1" /></td>
                </tr>
            </table>
        </form>
    </div>
</div>
<?php echo $footer; ?>
