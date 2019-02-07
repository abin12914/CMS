var alertType            = '';
var alertMessage         = '';
var ajaxStudentDetailUrl = '/ajax/student/details';

$(function () {
    $('body').on("click", "#add_address_button", function (evt) {
        evt.preventDefault();
        $('#address_modal').modal('show');
    });

    $('body').on("click", "#modal_form_submit", function (evt) {
        $.ajax({
            url: '/address',
            method: "post",
            data: $('#modal_address_form').serialize(),
            success: function(result) {
                
                if(result && result.flag) {
                    $('#modal_info').html("Success!");
                    $('#address_modal').modal('hide');
                    location.reload(true);
                } else {
                    $('#modal_info').html("Failed! Try again");
                }
            },
            error: function (err) {
                $('#modal_info').html("Invalid Data!");
            }
        });
    });

    $('body').on("keydown", "#student_code", function (evt) {
        //escape enter key form submission
        if(evt.keyCode == 13 || evt.key == 'Enter') {
            evt.preventDefault();
        }
    });

    $('body').on("keydown", "#registration_number", function (evt) {
        //escape enter key form submission
        if(evt.keyCode == 13 || evt.key == 'Enter') {
            evt.preventDefault();
        }
    });

    $('body').on("keydown", "#student_name", function (evt) {
        //escape enter key form submission
        if(evt.keyCode == 13 || evt.key == 'Enter') {
            evt.preventDefault();
        }
    });

    // ajax for importing student
    $('body').on("keyup", "#student_code", function (evt) {
        var studentCode = $(this).val();
        
        if(studentCode && studentCode != 'undefined' && studentCode.length > 2) {
            $('#registration_number').val('');
            $('#student_name').val('');
            $('#batch_id').val('');
            $('#batch_id').trigger('change');

            var searchArray = {
                'student_code': {
                    'paramName'     : 'student_code',
                    'paramOperator' : 'like',
                    'paramValue'    : '%'+studentCode+'%',
                }
            };
            //function call
            renderStudents(searchArray);            
        } else {
            $('.students_table_body').html('');
        }
        $('.student_id').prop('checked', false);
        $('#student_id_select_all').prop('checked', false);
    });

    // ajax for importing student
    $('body').on("keyup", "#registration_number", function (evt) {
        var studentRegistrationNumber = $(this).val();
        
        if(studentRegistrationNumber && studentRegistrationNumber != 'undefined' && studentRegistrationNumber.length > 2) {
            $('#student_code').val('');
            $('#student_name').val('');
            $('#batch_id').val('');
            $('#batch_id').trigger('change');

            var searchArray = {
                'registration_number': {
                    'paramName'     : 'registration_number',
                    'paramOperator' : 'like',
                    'paramValue'    : '%'+studentRegistrationNumber+'%',
                }
            };
            //function call
            renderStudents(searchArray);            
        } else {
            $('.students_table_body').html('');
        }
        $('.student_id').prop('checked', false);
        $('#student_id_select_all').prop('checked', false);
    });

    // ajax for importing student
    $('body').on("keyup", "#student_name", function (evt) {
        var studentName = $(this).val();
        
        if(studentName && studentName != 'undefined' && studentName.length > 2) {
            $('#student_code').val('');
            $('#registration_number').val('');
            $('#batch_id').val('');
            $('#batch_id').trigger('change');

            var searchArray = {
                'name': {
                    'paramName'     : 'name',
                    'paramOperator' : 'like',
                    'paramValue'    : '%'+studentName+'%',
                }
            };
            /*var searchArray = {'name': studentName};*/
            //function call
            renderStudents(searchArray);            
        } else {
            $('.students_table_body').html('');
        }
        $('.student_id').prop('checked', false);
        $('#student_id_select_all').prop('checked', false);
    });

    // ajax for importing student
    $('body').on("change", "#batch_id", function (evt) {
        var batchId = $(this).val();
        
        if(batchId && batchId != 'undefined') {
            $('#student_name').val('');
            $('#student_code').val('');

            var searchArray = {
                'batch_id': {
                    'paramName'     : 'batch_id',
                    'paramOperator' : '=',
                    'paramValue'    : batchId,
                }
            };
            /*var searchArray = {'batch_id': batchId};*/
            //function call
            renderStudents(searchArray);  
        } else {
            $('.students_table_body').html('');
        }
        $('.student_id').prop('checked', false);
        $('#student_id_select_all').prop('checked', false);
    });

    // ajax for importing student
    $('body').on("click", "#student_id_select_all", function (evt) {
        $('.student_id').prop('checked', this.checked);
    });
});

function renderStudents(searchArray) {
    $.ajax({
        url: ajaxStudentDetailUrl,
        method: "post",
        data: {
            searchParams : searchArray,
        },
        success: function(result) {
            
            if(result && result.flag) {
                    var students = result.students;
                    var htmlCode = '';

                    $.each(students, function(index, student) {
                        htmlCode += "<tr>"+
                                        "<td>"+ (index+1)+ "</td>"+
                                        "<td>"+ " "+ student.name+ " - "+ student.student_code+ "</td>"+
                                        "<td>"+ (student.gender == 1 ? 'Male' : 'Female') + "</td>"+
                                        "<td>"+ student.address+ "</td>"+
                                        "<td>"+ student.batch.course.course_name+ " ["+ student.batch.from_year+ " - "+ student.batch.to_year+ "]"+ "</td>"+
                                        "<td>"+ student.batch.course.university.university_name+ "</td>"+
                                        "<td>"+ (student.registration_number || 'N/A') +"</td>"+
                                        "<td><label><input type='checkbox' class='minimal student_id' name='student_id[]' value='"+ student.id+ "'></label></td>"+
                                    "</tr>";
                        $('.students_table_body').html(htmlCode);
                    });
            } else {
                $('.students_table_body').html('');
            }
        },
        error: function (err) {
            $('.students_table_body').html('');
        }
    });
}