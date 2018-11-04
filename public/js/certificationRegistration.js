var alertType       = '';
var alertMessage    = '';

$(function () {
    var ajaxStudentDetailUrl = '/ajax/student/details';

    // for checking if the pressed key is a number
    $('body').on("keyup", "#student_code", function (evt) {
        var studentCode = $(this).val();
        
        if(studentCode && studentCode != 'undefined') {
            var searchArray = {'student_code': studentCode};

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
                                htmlCode += "<tr><td></td><td></td><td></td><td></td><td></td><td></td>"
                            });
                    } else {
                        //student not found
                    }
                },
                error: function (err) {
                    //student not found
                }
            });
        } else {
            //student not found
        }
    });
});