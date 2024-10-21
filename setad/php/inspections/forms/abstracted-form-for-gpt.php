<form id="myForm" action="submit_form.php" method="post">
    <div class="form-group">
        <label>1- عملکرد اداره نظارت و بازرسی در سال 1402 در خصوص نظارت و بازرسی مراکز طرف قرارداد به شرح
            زیر می‌باشد:</label>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>نوع مرکز</th>
                    <th>پزشک عمومی</th>
                    <th>پزشک متخصص</th>
                    <th>داروخانه</th>
                    <th>دندانپزشک</th>
                    <th>پاراکلینیک</th>
                    <th>درمانگاه</th>
                    <th>مرکز جراحی محدود و بیمارستان</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>تعداد مراکز</td>
                    <td><input type="text" name="general_practitioner_centers" class="form-control"></td>
                    <td><input type="text" name="specialist_centers" class="form-control"></td>
                    <td><input type="text" name="pharmacy_centers" class="form-control"></td>
                    <td><input type="text" name="dentist_centers" class="form-control"></td>
                    <td><input type="text" name="paraclinic_centers" class="form-control"></td>
                    <td><input type="text" name="clinic_centers" class="form-control"></td>
                    <td><input type="text" name="surgery_centers" class="form-control"></td>
                </tr>
                ... (other <tr> elements were removed for abstraction)
            </tbody>
        </table>
    </div>

    <div class="form-group">
        <label>3- آیا کارشناسان سایر واحدها در امر بازرسی مشارکت دارند؟</label>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="participation" id="participation_yes" value="yes">
            <label class="form-check-label" for="participation_yes">
                بلی
            </label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="participation" id="participation_no" value="no">
            <label class="form-check-label" for="participation_no">
                خیر
            </label>
        </div>
    </div>

    <div class="form-group">
        <label>الف) نظارت و بازرسی‌های حضوری:</label>
        <textarea class="form-control" name="in_person_supervision" rows="3"></textarea>
    </div>


    <br />
    <br />
    <button type="submit" class="btn btn-primary" onclick="save()">ثبت</button>
    <button type="submit" class="btn btn-primary" onclick="return_()">بازگشت</button>
    <button type="submit" class="btn btn-primary" onclick="saveAndReturn()">ثبت و بازگشت</button>

</form>