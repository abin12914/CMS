var alertType            = '';
var alertMessage         = '';
var ajaxStudentDetailUrl = '/ajax/student/details';

$(function () {
    // ajax for importing student
    $('body').on("keyup", "#student_code", function (evt) {
        var studentCode = $(this).val();
        
        if(studentCode && studentCode != 'undefined') {
            var searchArray = {'student_code': studentCode};
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
            var searchArray = {'batch_id': batchId};
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
                                        "<td>"+ student.title+ " "+ student.name+ " - "+ student.student_code+ "</td>"+
                                        "<td>"+ student.address+ "</td>"+
                                        "<td>"+ student.batch.course.course_name+ " ["+ student.batch.from_year+ " - "+ student.batch.to_year+ "]"+ "</td>"+
                                        "<td>"+ student.batch.course.university+ "</td>"+
                                        "<td>"+ student.batch.fee_amount+"</td>"+
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