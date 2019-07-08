<?php $this->load->view('auth/header'); ?>

<div class="container">
    <h1>Login with barcode</h1>
    <?php echo form_open('housekeeper/login', 'class="item1"'); ?>
    <fieldset class="content">
        <input type="text" name="barcode" />

        <div class="buttonSection">
            <button type="submit" name="submit">Login</button>
        </div>
    </fieldset>
    </form>

    <h1>Login with email and password</h1>
    <?php echo form_open('auth/login', 'class="item2"'); ?>
    <fieldset class="content">
        <label for="email">Email</label>
        <input id="email" type="email" name="identity" autofocus/>

        <label for="password">Password</label>
        <input id="password" type="password" name="password" />

        <div class="buttonSection">
            <button type="submit" name="submit">Login</button>
        </div>
    </fieldset>
    </form>

    <div class="item3">
        <div class="content">

            <p class="toggle">Forgot/Update password</p>

            <div class="hidden center">
                <?php echo form_open('auth/forgot_password'); ?>
                <fieldset class="content">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="identity" />

                    <div class="buttonSection">
                        <button type="submit" name="forgot_password_submit_btn">Submit</button>
                    </div>
                </fieldset>
                </form>
            </div>

            <p class="toggle">Update email</p>

            <div class="hidden center">
                <?php echo form_open('housekeeper/updateEmail'); ?>
                <fieldset class="content">
                    <label for="barcode">Barcode</label>
                    <input id="barcode" type="text" name="barcode" />
                    <div class="wrapper">
                        <label for="email">New email</label>
                        <input id="email" type="email" name="updatedEmail" />
                    </div>
                    <div class="wrapper">
                        <label for="email">Confirm new email</label>
                        <input id="email" type="email" name="updatedEmail_confirm" />
                    </div>
                    <div class="wrapper">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" />
                    </div>
                    <div class="buttonSection">
                        <button type="submit" name="submit">Change</button>
                    </div>
                </fieldset>
                </form>
            </div>

            <p class="toggle">Update Barcode</p>

            <div class="hidden center">
                <?php echo form_open('housekeeper/updateBarcode'); ?>
                <fieldset class="content">
                    <label for="barcode">Barcode</label>
                    <input id="barcode" type="text" name="updatedBarcode" />
                    <div class="wrapper">
                        <label for="email">New email</label>
                        <input id="email" type="email" name="email" />
                    </div>
                    <div class="wrapper">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" />
                    </div>
                    <div class="buttonSection">
                        <button type="submit" name="submit">Change</button>
                    </div>
                </fieldset>
                </form>
            </div>

            <p class="toggle">Set barcode login</p>

            <div class="hidden center">
                <?php echo form_open('housekeeper/toggleBarcodeLogin'); ?>
                <fieldset class="content">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" />
                    <div class="wrapper">
                        <label for="password">Password</label>
                        <input id="password" type="password" name="password" />
                    </div>
                    <div class="buttonSection">
                        <button type="submit" name="disable" value="disable">Disable</button>
                        <button type="submit" name="enable" value="enable">Enable</button>
                    </div>
                </fieldset>
                </form>
            </div>

        </div>
    </div>

</div>

<script>
    document.querySelectorAll('.toggle').forEach((toggle) => {
        toggle.addEventListener('click', () => {
            console.log(toggle.nextElementSibling.classList.toggle('hidden'));
        });
    });
</script>

</body>

</html>