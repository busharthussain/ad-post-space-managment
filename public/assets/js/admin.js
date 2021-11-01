error = true;
if (typeof ($batchId) === 'undefined') {
    $batchId = '';
}
$(document).ready(function () {
    $(document).on("click", '.paq-pager ul.pagination a', function (e) {
        e.preventDefault();
        $page = $(this).attr('href').split('page=')[1];
        $type = $defaultType;
        updateFormData();
        renderAdmin();
    });

    $('body').on('click', '.delete_content', function (e) {
        e.preventDefault();
        if (typeof ($viewOnly) === 'undefined' || $viewOnly != 1) {
            $deleteId = this.id;
            var result = confirm(_lang('Are you sure to delete'));
            if (result) {
                $type = 'delete';
                $formData = {
                    '_token': $token,
                    'id': $deleteId
                };
                renderAdmin();
            }
        }
    });

    $('body').on('click', '.view_contact', function (e) {
        e.preventDefault();
        $type = 'viewPopup';
        $formData = {
            '_token': $token,
            'id': this.id
        };
        renderAdmin();
    });

    $('body').on('click', '.sorting', function (e) {
        e.preventDefault();
        $('.sorting').not(this).removeClass('fa-sort-asc fa-sort-desc').addClass('fa-sort');
        $sortColumn = $(this).parent().attr("id");
        if ($(this).hasClass('fa-sort-' + $asc)) {
            $(this).removeClass('fa-sort-' + $asc).addClass('fa-sort-' + $desc);
            $sortType = 'desc';
        } else if ($(this).hasClass('fa-sort-' + $desc)) {
            $(this).removeClass('fa-sort-' + $desc).addClass('fa-sort-' + $asc);
            $sortType = 'asc';
        } else {
            $(this).addClass('fa-sort-' + $asc);
            $sortType = 'asc';
        }
        $type = $defaultType;
        updateFormData();
        renderAdmin();
    });

    $('#search').keydown(function (e) {
        if (e.keyCode == 13) {
            event.preventDefault();
            $search = $(this).val();
            $page = 1;
            updateFormData();
            $type = $defaultType;
            renderAdmin();
        }
    });

});

/**
 * This is used to control admin all functions
 */
function renderAdmin() {
    /**
     * This is user to render grid data on base of grid fields
     */
    var renderGrid = function () {
        $html = '';
        $result = $data.result;
        $gridFields = $data.gridFields;
        $('#total-record').html('[' + $data.total + ']');
        $(".paq-pager").html($data.pager);
        if ($result != '') {
            $.each($result, function (i, v) {
                $keyValue = v;
                $blockedDisplay = '';
                if (typeof (v.company_id) != 'undefined' && v.company_id == 0) {
                    $blockedDisplay = 'special-color';
                }
                $html += '<tr id="row_' + $keyValue.id + '" class="' + $blockedDisplay + '">';
                $.each($gridFields, function (index, value) {
                    $columValue = v[value.name];
                    console.log(value);
                    if (typeof (value.custom) !== 'undefined' && typeof (value.custom.isDownloadLink) !== 'undefined') {
                        if (typeof($keyValue.qr_code) !== 'undefined' && $keyValue.qr_code)
                            $html += '<td id="column_' + value.name + '_' + $keyValue.id + '"><a href="'+$qrcodeDownloadRoute+'/'+$keyValue.id+'" id="'+$keyValue.id+'" class="download_qr">'+_lang('Download')+'</a></td>';
                        else
                            $html += '<td id="column_' + value.name + '_' + $keyValue.id +'"></td>';
                    } else if (typeof (value.custom) !== 'undefined' && typeof (value.custom.status) !== 'undefined') {
                        $titleValue = value.custom.emptyTitle;
                        if ($columValue !='' && $columValue != 0) {
                            $titleValue = value.custom.value;
                        }
                        $html += '<td id="column_' + value.name + '_' + $keyValue.id + '">' + $titleValue + '</td>';
                    } else if(typeof (value.custom) !== 'undefined' && typeof (value.custom.isAnchor) !== 'undefined') {
                        $viewOnly = '';
                        if (typeof (value.custom.viewOnly) == 'undefined') {
                            $viewOnly = '?viewOnly=true';
                        }
                        $html += '<td id="column_' + value.name + '_' + $keyValue.id + '"><a href="'+value.custom.url+'/'+$keyValue.id+''+$viewOnly+'">' + $columValue + '</a></td>';
                    } else if (typeof (value.custom) !== 'undefined' && typeof (value.custom.image) !== 'undefined') {
                        $imageURL = value.custom.imageURL;
                        if(typeof ($keyValue.parent_category_id) !== 'undefined' && $keyValue.parent_category_id == 3 && $keyValue.wanted_unique_image == 1) {
                            $imageURL = value.custom.imageURLCategory;
                        }
                        $html += '<td id="column_' + value.name + '_' + $keyValue.id + '" width="'+value.custom.width+'"><a href="javascript: void(0)"><img src="'+$imageURL+'/'+$columValue+'"></a></td>';
                    } else {
                        $html += '<td id="column_' + value.name + '_' + $keyValue.id + '">' + isNull($columValue) + '</td>';
                    }
                });
                var fn = window[$defaultType+'Action'];

                if (typeof fn === 'function') { // used to trigger relative action
                    fn();
                }
                $html += '</tr>';
            });
        }

        $('#page-data').html($html);
        if ($type == 'renderPostChats' && $("#post-chat").length) {
            setTimeout(function(){
                $("html, body").animate({ scrollTop: $(document).height() }, 300);
                $('.chat-box').animate({
                        scrollTop: $("#post-chat > li:last-child").offset().top
                    },
                    'slow');
            }, 500);
        }
    };

    /**
     * This is used to upload image
     */
    var uploadImage = function () {
        $('.uploader').dmUploader({
            url: $uploadImageRoute,
            allowedTypes: 'image/*',
            dataType: 'json',
            onBeforeUpload: function (id) {
                $('.uploader').data('dmUploader').settings.extraData = {
                    "_token": $token,
                    tempImageId: $tempImageId,
                    batchId: $batchId
                };
            },
            onNewFile: function (id, file) {
                $.danidemo.addFile('#demo-files', id, file);

                /*** Begins Image preview loader ***/
                if (typeof FileReader !== "undefined") {

                    var reader = new FileReader();

                    // Last image added
                    var img = $('#demo-files').find('.demo-image-preview').eq(0);

                    reader.onload = function (e) {
                        img.attr('src', e.target.result);
                    }

                    reader.readAsDataURL(file);

                } else {
                    // Hide/Remove all Images if FileReader isn't supported
                    $('#demo-files').find('.demo-image-preview').remove();
                }
                /*** Ends Image preview loader ***/

            },
            onUploadProgress: function (id, percent) {
                var percentStr = percent + '%';
                $.danidemo.updateFileProgress(id, percentStr);
            },
            onUploadSuccess: function (id, data) {
                notificationMsg(data.message, data.success);
                uploadMessages(id);
                if (typeof ($isUploadImage) !== 'undefined') {
                    $isUploadImage = true;
                }
                if (data.success == true) {
                    if (typeof $imageType != 'undefined' && $imageType == true) {
                        location.reload(true);
                    }
                    if (typeof ($appendImage) !== 'undefined' && $appendImage == true) {
                        $appendHtml = '<tr id="row_'+data.id+'">\n' +
                            '<td width="17%"><a href="javascript: void(0)"><img src="'+data.filePath+'" alt="image"></a></td>\n' +
                            '<td width="70%">'+data.fileName+'</td>\n' +
                            '<td width="13%"><a href="javascript: void(0)" id="delete_'+data.id+'" class="delete_content">'+_lang('DELETE')+'</a></td>\n' +
                            '</tr>';
                        $('#post_images').append($appendHtml);
                    } else {
                        $tempImageId = data.tempImageId;
                        $('#company-image').html('<img src="'+data.fileName+'" />');
                    }
                    $.danidemo.updateFileStatus(id, 'success', 'Upload Complete');
                    $.danidemo.updateFileProgress(id, '100%');
                }
            },
            onUploadError: function (id, message) {
                //notificationMsg(message, error);
            },
            onFileTypeError: function (file) {
                notificationMsg('File \'' + file.name + '\' cannot be added: must be an Image', error);
            },
            onFileSizeError: function (file) {
                //notificationMsg('File \'' + file.name + '\' cannot be added: size excess limit', error);
            },
            onFallbackMode: function (message) {
                //notificationMsg('Browser not supported(do something else here!): ' + message, error);
            }
        });
    }

    /**
     * This is used to community images
     */
    var communityUploader = function () {
        $('.uploader_image,.uploader_borrow,.uploader_swap,.uploader_giveaway, .uploader_wanted').dmUploader({
            url: $uploadImageRoute,
            dataType: 'json',
            maxFileSize: 5242880,
            onBeforeUpload: function (id) {
                $fileClickedId  = $($(this).context.childNodes[1]).attr('id').split('_')[1];
                $uploaderClass = 'uploader_' + $fileClickedId;
                $('.'+$uploaderClass).data('dmUploader').settings.extraData = {
                    "_token": $token,
                    id: $id,
                    clickedId: $fileClickedId
                };
            },
            onNewFile: function (id, file) {
                $.danidemo.addFile('#demo-files', id, file);

                /*** Begins Image preview loader ***/
                if (typeof FileReader !== "undefined") {

                    var reader = new FileReader();

                    // Last image added
                    var img = $('#demo-files').find('.demo-image-preview').eq(0);

                    reader.onload = function (e) {
                        img.attr('src', e.target.result);
                    }

                    reader.readAsDataURL(file);

                } else {
                    // Hide/Remove all Images if FileReader isn't supported
                    $('#demo-files').find('.demo-image-preview').remove();
                }
                /*** Ends Image preview loader ***/

            },
            onUploadProgress: function (id, percent) {
                var percentStr = percent + '%';
                $.danidemo.updateFileProgress(id, percentStr);
            },
            onUploadSuccess: function (id, data) {
                if (data.success == false)
                    notificationMsg(data.msg, data.success);
                uploadMessages(id);
                if (data.success == true) {
                    $.danidemo.updateFileStatus(id, _lang('success'), _lang('Upload Complete'));
                    $.danidemo.updateFileProgress(id, '100%');
                    $id = data.id;
                    if ($arrUploadedImages.indexOf(data.clickedId) == -1 && data.clickedId !== 'image')
                        $arrUploadedImages.push(data.clickedId);
                    console.log($arrUploadedImages);
                    $('#column_' + data.clickedId).html('<img src="'+data.fileName+'">');
                }
            },
            onUploadError: function (id, message) {
                uploadMessages(id);
                notificationMsg(message, error);
            },
            onFileTypeError: function (file) {
                adminNotificationMsg('File \'' + file.name + '\' cannot be added: must be an ' + allowedTypeError + '', 'error');
            },
            onFileSizeError: function (file) {
                adminNotificationMsg(_lang('Your image ')+file.name+_lang(' cannot be uploaded. Maximum size per image is 5MB.'), 'error', true);
            },
            onFallbackMode: function (message) {
                notificationMsg(_lang('Browser not supported(do something else here!): ') + message, 'error');
            }
        });
    }

    var uploadPdf = function () {
        $('.uploader-pdf').dmUploader({
            url: $uploadPdfRoute,
            dataType: 'json',
            onBeforeUpload: function (id) {
                $('.uploader-pdf').data('dmUploader').settings.extraData = {
                    "_token": $token,
                    tempImageId: $tempImageId,
                    isPdf: true
                };
            },
            onNewFile: function (id, file) {
                $.danidemo.addFile('#demo-files', id, file);

                /*** Begins Image preview loader ***/
                if (typeof FileReader !== "undefined") {

                    var reader = new FileReader();

                    // Last image added
                    var img = $('#demo-files').find('.demo-image-preview').eq(0);

                    reader.onload = function (e) {
                        img.attr('src', e.target.result);
                    }

                    reader.readAsDataURL(file);

                } else {
                    // Hide/Remove all Images if FileReader isn't supported
                    $('#demo-files').find('.demo-image-preview').remove();
                }
                /*** Ends Image preview loader ***/

            },
            onUploadProgress: function (id, percent) {
                var percentStr = percent + '%';
                $.danidemo.updateFileProgress(id, percentStr);
            },
            onUploadSuccess: function (id, data) {
                notificationMsg(data.message, data.success);
                uploadMessages(id);
                if (data.success == true) {
                    $tempImageId = data.tempImageId;
                    $('#privacy-document').html(data.fileName);
                    $.danidemo.updateFileStatus(id, 'success', _lang('Upload Complete'));
                    $.danidemo.updateFileProgress(id, '100%');
                }
            },
            onUploadError: function (id, message) {
                //notificationMsg(message, error);
            },
            onFileTypeError: function (file) {
                //notificationMsg('File \'' + file.name + '\' cannot be added: must be an ' + allowedTypeError + '', error);
            },
            onFileSizeError: function (file) {
                //notificationMsg('File \'' + file.name + '\' cannot be added: size excess limit', error);
            },
            onFallbackMode: function (message) {
                //notificationMsg('Browser not supported(do something else here!): ' + message, error);
            }
        });
    }

    var uploadMessages = function (id) {
        $('#demo-file' + id).slideToggle(500);
        setTimeout(function () {
            $('#demo-file' + id).remove();
        }, 600);
    }

    /**
     * This is used to render companies action
     */
    renderCompaniesAction = function () {
        $id = $keyValue.id;
        $html += '<td>\n' +
            '                <ul>\n' +
            '                    <li><a href="'+$usersRoute+'/'+$id+'"><i class="fa fa-user"></i></a></li>\n' +
            '                    <li><a href="'+$communityRoute+'/'+$id+'"><i class="fa fa-users"></i></a></li>\n' +
            '                    <li><a href="'+$editRoute+'/'+$id+'"><i class="fa fa-pencil-square-o"></i></a></li>\n' +
            '                    <li><a href="javascript: void(0)" id="delete_'+$id+'" class="delete_content"><i class="fa fa-trash-o"></i></a></li>\n' +
            '                </ul>\n' +
            '            </td>';
    }

    /**
     * This is used to render communites actions
     */
    renderCommunitesAction = function () {
        $id = $keyValue.id;
        $invitationLink = '';
        if(!$isAdminRole) {
            $invitationLink = '<li><a href="'+$invitationRoute+'/'+$id+'"><i class="fa fa-user-plus"></i></a></li>\n';
        }
        if ($isAdminRole || $keyValue.company_id > 0) {
            $html += '<td>\n' +
                '<ul>\n' +
                $invitationLink +
                '<li><a href="'+$editRoute+'/'+$id+'"><i class="fa fa-pencil-square-o"></i></a></li>\n' +
                '<li><a href="javascript: void(0)" id="delete_'+$id+'" class="delete_content"><i class="fa fa-trash-o"></i></a></li>\n' +
                '</ul>\n' +
                '</td>';
        } else {
            $html += '<td></td>';
        }
    }

    /**
     * This is used to render posts
     */
    renderPostsAction = function () {
        $id = $keyValue.id;
        $html += '<td>\n' +
            '<ul>\n' +
            '<li><a href="'+$messageRoute+'/'+$id+'"><i class="fa fa-comment-o"></i></a></li>\n' +
            '<li><a href="'+$editRoute+'/'+$id+'"><i class="fa fa-pencil-square-o"></i></a></li>\n' +
            '<li><a href="javascript: void(0)" id="delete_'+$id+'" class="delete_content"><i class="fa fa-trash-o"></i></a></li>\n' +
            '</ul>\n' +
            '</td>';
    }

    /**
     * This is used to render ads action
     */
    renderAdsAction = function () {
        $id = $keyValue.id;
        $html += '<td>\n' +
            '<ul>\n' +
            '<li><a href="'+$editRoute+'/'+$id+'"><i class="fa fa-pencil-square-o"></i></a></li>\n' +
            '<li><a href="javascript: void(0)" id="delete_'+$id+'" class="delete_content"><i class="fa fa-trash-o"></i></a></li>\n' +
            '</ul>\n' +
            '</td>';
    }

    /**
     * This is used to render sub admins action
     */
    renderSubAdminsAction = function () {
        $id = $keyValue.id;
        $html += '<td>\n' +
            '<ul>\n' +
            '<li><a href="'+$editRoute+'/'+$id+'"><i class="fa fa-pencil-square-o"></i></a></li>\n' +
            '<li><a href="javascript: void(0)" id="delete_'+$id+'" class="delete_content"><i class="fa fa-trash-o"></i></a></li>\n' +
            '</ul>\n' +
            '</td>';
    }

    renderJoinCommunitesAction = function () {
        $id = $keyValue.id;
        $html += '<td width="19%">\n' +
            '<a href="javascript: void(0)" id="community_'+$id+'_1" class="btn btn-success join-community">Accept</a>\n' +
            '<a href="javascript: void(0)" id="community_'+$id+'_0" class="btn btn-danger join-community">Decline</a>\n' +
            '</td>';
    }

    /**
     * This is used to render notification actions
     */
    renderNotificationsAction = function () {
        $id = $keyValue.id;
        $html += '<td>\n' +
            '<ul>\n' +
            '<li><a href="javascript: void(0)" class="resend_notifications" id="resend_'+$id+'"><i class="fa fa-paper-plane"></i></a></li>\n' +
            '<li><a href="'+$editRoute+'/'+$id+'"><i class="fa fa-pencil-square-o"></i></a></li>\n' +
            '<li><a href="javascript: void(0)" id="delete_'+$id+'" class="delete_content"><i class="fa fa-trash-o"></i></a></li>\n' +
            '</ul>\n' +
            '</td>';
    }

    /**
     * This is used to render reported posts
     */
    renderReportedPostsAction = function () {
        $id = $keyValue.id;
        $reported_id = $keyValue.reported_id;
        $user_id = $keyValue.user_id;
        $html += '<td>\n' +
            '<ul>\n' +
            '<li><a href="javascript: void(0)" id="view_' + $user_id + '_'+$reported_id+'" class="view_contact"><i class="fa fa-eye"></i></a></li>\n' +
            '<li><a href="javascript: void(0)" id="report_' + $reported_id+'" class="report_post_message"><i class="fa fa-envelope"></i></a></li>\n' +
            '<li><a href="'+$editRoute+'/'+$id+'"><i class="fa fa-pencil-square-o"></i></a></li>\n' +
            '<li><a href="javascript: void(0)" id="delete_' + $id + '_'+$reported_id+'" class="delete_content"><i class="fa fa-trash-o"></i></a></li>\n' +
            '</ul>\n' +
            '</td>';
    }
    /**
     * This is used to render grid routes
     */
    var callGridRender = function () {
        ajaxStartStop();
        $.ajax({
            url: $renderRoute,
            type: 'POST',
            data: $formData,
            success: function (data) {
                $data = data;
                renderGrid();
            },
            error: function ($error) {
                notificationMsg($error, error);
            }
        });
    };

    var sorter = function () {
        // will be add later
    }

    /**
     * This is used to make companies drowndown
     */
    var companiesDropDown = function () {
        $.ajax({
            url: $companiesDropDownRoute,
            type: 'POST',
            data: $formData,
            success: function (data) {
                $dropdown = '';
                $.each(data, function (i,v) {
                    $dropdown += '<option value="'+v.id+'">'+v.name+'</option>';
                })
                $('#companies').html($dropdown);
                $('#companies')[0].sumo.reload();
            },
            error: function ($error) {
                // notificationMsg($error, error);
            }
        });
    }

    /**
     * This is used to get communities drop down data
     */
    var communitiesDropDown = function () {
        $.ajax({
            url: $communitiesDropDownRoute,
            type: 'POST',
            data: $formData,
            success: function (data) {
                $dropdown = '';
                $.each(data, function (i,v) {
                    $dropdown += '<option value="'+v.id+'">'+v.title+'</option>';
                })
                $('#communities').html($dropdown);
                $('#communities')[0].sumo.reload();
            },
            error: function ($error) {
                // notificationMsg($error, error);
            }
        });
    }

    /**
     * This is used to accept/reject join community request
     */
    var joinCommunityAction = function () {
        $.ajax({
            url: $actionRoute,
            type: 'POST',
            data: $formData,
            success: function (data) {
                notificationMsg(data.message, data.success);
                if (data.success == true) {
                    $type = $defaultType;
                    updateFormData();
                    renderAdmin();
                }
            },
            error: function ($error) {
                // notificationMsg($error, error);
            }
        });
    }

    /**
     * This is used to display communities/user data
     */
    var getCommunitiesOrUsers = function () {
        ajaxStartStop();
        $.ajax({
            url: $getCommunitiesRoute,
            type: 'POST',
            data: $formData,
            success: function (data) {
                $data = data.data;
                $ids = data.ids;
                if ($ids.length) {
                    var $ids = $ids.split(',').map(function (item) {
                        return parseInt(item, 10);
                    });
                }
                $html = '';
                if ($data.length) {
                    $.each($data, function (i,v) {
                        $checked = '';
                        if (jQuery.inArray(parseInt(v.id), $ids) !== -1) {
                            $checked = 'checked';
                        }
                        console.log(v.id);
                        console.log($ids);
                        console.log($checked);
                        $html += '<tr>\n' +
                            '<td width="83%">'+v.name+'</td>\n' +
                            '<td width="13%">' +
                            '<div class="checkbox checkbox-primary pull-right p-t-0">\n' +
                            '<input id="checkbox_'+v.id+'"  class = "checkbox" type="checkbox" name="ids[]" value="'+v.id+'" '+$checked+'>\n' +
                            '<label for="checkbox_'+v.id+'"> </label>\n' +
                            '</div>\n' +
                            '</td>\n' +
                            '</tr>';
                    });
                }
                $('#community-users').html($html);
            },
            error: function ($error) {
                // notificationMsg($error, error);
            }
        });
    }

    /**
     * This is common function used to add record
     */
    var addRecord = function () {
        ajaxStartStop();
        $.ajax({
            url: $addRecordRoute,
            type: 'POST',
            data: $formData,
            success: function (data) {
                $message = data.message;
                if (data.success == true) {
                    notificationMsg(data.message, data.success);
                    if (typeof ($redirectRoute) !== 'undefined') {
                        window.location = $redirectRoute;
                    }
                } else {
                    if ($.isArray(data.message)) {
                        $message = '';
                        $.each(data.message, function (i, v) {
                            $message += v + "\n";
                        })
                    }
                }
                notificationMsg($message, data.success);
            },
            error: function ($error) {
                notificationMsg($error, 'error');
            }
        });
    }

    /**
     * This is general function used to delete content
     */
    var destroy = function () {
        ajaxStartStop();
        $.ajax({
            url: $deleteRoute,
            type: 'POST',
            data: $formData,
            success: function (data) {
                if (data.success == true) {
                    $('#row_' + data.id).remove();
                    if (typeof ($reloadAfterDelete) == 'undefined') {
                        $page = 1;
                        updateFormData();
                        $type = $defaultType;
                        renderAdmin();
                    }
                }
                notificationMsg(data.message, data.success);
            },
            error: function ($error) {
                notificationMsg($error, error);
            }
        });
    }

    /**
     * This is used to view popup
     */
    var viewPopup = function () {
        ajaxStartStop();
        $.ajax({
            url: $viewPopupRoute,
            type: 'POST',
            data: $formData,
            success: function (data) {
                $('#myModal').html(data.view);
                $('#myModal').modal('show');
            },
            error: function ($error) {
                notificationMsg($error, error);
            }
        });
    }

    /**
     * This is used to report post message
     */
    var reportPostMessage = function () {
        ajaxStartStop();
        $.ajax({
            url: $reportPostMessageRoute,
            type: 'POST',
            data: $formData,
            success: function (data) {
                $('#myModal').html(data.view);
                $('#myModal').modal('show');
            },
            error: function ($error) {
                notificationMsg($error, error);
            }
        });
    }

    var sendReportPostMessage = function () {
        ajaxStartStop();
        $.ajax({
            url: $sendReportPostMessageRoute,
            type: 'POST',
            data: $formData,
            success: function (data) {
                $('#myModal').modal('hide');
                notificationMsg(data.message, data.success);
            },
            error: function ($error) {
                notificationMsg($error, error);
            }
        });
    }

    /**
     * This is used to send post message
     */
    var sendPostMessage = function() {
        ajaxStartStop();
        $.ajax({
            url: $sendPostMessageRoute,
            type: 'POST',
            data: $formData,
            success: function (data) {
                if (data.success == true) {
                    location.reload(true);
                }
            },
            error: function ($error) {
                notificationMsg($error, error);
            }
        });
    }

    /**
     * This is used to render chats data
     */
    var renderPostChats = function () {
        ajaxStartStop();
        $.ajax({
            url: $renderRoute,
            type: 'POST',
            data: $formData,
            success: function (data) {
                $html = '';
                $.each(data, function (i,v) {
                    if (typeof (v.image_1) == 'undefined' && (v.sender_id == v.request_id || v.sender_id == 1)) { // This is sender message
                        if (v.message != '' && v.message != null) {
                            $html += '<li>\n' +
                                '<div class="chat-img"><img src="' + $assetURL + '/' + v.relative_path + '/' + v.image + '" alt="image"></div>\n' +
                                '<div class="chat-content">\n' +
                                '<h5>' + v.name + '</h5>\n' +
                                '<div class="box bg-light-info">' + v.message + '</div>\n' +
                                '</div>\n' +
                                '<div class="chat-time">' + v.created_at + '</div>\n' +
                                '</li>';
                        }
                        if (v.post_image != '' && v.post_image != null) {
                         $html += '<li>\n' +
                                    '<div class="chat-img"><img src="' + $assetURL + '/' + v.relative_path + '/' + v.image + '" alt="user" /></div>\n' +
                                        '<div class="chat-content">\n' +
                                        '<h5>'+v.name+'</h5>\n' +
                                            '<div class="box bg-light-info chat-img-holder"><a class="post_image" href="' + $assetURL + '/' + $relativePathPost + '/' + v.post_image + '"><img src="' + $assetURL + '/' + $relativePathPost + '/' + v.post_image + '" alt="image"></a></div>\n' +
                                        '</div>\n' +
                                    '<div class="chat-time">'+v.created_at+'</div>\n' +
                                    '</li>';
                            }
                    } else if(typeof (v.image_1) == 'undefined' && v.sender_id == v.request_receiver_id) { // this is receiver message
                        if (v.message != '' && v.message != null) {
                            $html += '<li class="reverse">\n' +
                                '<div class="chat-content">\n' +
                                '<h5>' + v.name + '</h5>' +
                                '<div class="box bg-light-inverse">' + v.message + '</div>\n' +
                                '</div>\n' +
                                '<div class="chat-img"><img src="' + $assetURL + '/' + v.relative_path + '/' + v.image + '" alt="image"></div>\n' +
                                '<div class="chat-time">' + v.created_at + '</div>\n' +
                                '</li>';
                        }
                        if (v.post_image != '' && v.post_image != null) {
                            $html += '<li class="reverse">\n' +
                                '<div class="chat-content">\n' +
                                '    <h5>'+v.name+'</h5>\n' +
                                '    <div class="box bg-light-inverse chat-img-holder">\n' +
                                '        <a class="post_image" href="' + $assetURL + '/' + $relativePathPost + '/' + v.post_image + '"><img src="' + $assetURL + '/' + $relativePathPost + '/' + v.post_image + '" alt="user" /></a>\n' +
                                '    </div>\n' +
                                '</div>\n' +
                                '<div class="chat-img"><img src="' + $assetURL + '/' + v.relative_path + '/' + v.image + '" alt="user" /></div>\n' +
                                '<div class="chat-time">10:57 am</div>\n' +
                                '</li>';
                        }
                    } else { // This is first message
                        $borrowFromTo = $firstMessage = '';
                        if (v.date_to != '' && v.date_from != null) {
                            $borrowFromTo = '<div><span>Borrow From: ' + v.date_from + '</span><span>Borrow To: ' + v.date_to + '</span></div>\n';
                        }
                        $html += '<li><div class="chat-img"><img src="' + $assetURL + '/' + v.relative_path + '/' + v.image + '" alt="image"></div>\n' +
                            '<div class="chat-content">\n' +
                            '<h5>'+v.name+'</h5>\n' +
                            '<div class="box bg-light-info">'+v.message+'</div>\n' +
                            '</div>\n' +
                            '<div class="chat-time">'+v.created_at+'</div>\n' +
                            $firstMessage +
                            '</li>';
                        for($i = 1; $i < 4; $i ++) {
                            $colum = 'image_' + $i;
                            if(v[$colum] != '' && v[$colum] != null) {
                                $html += '<li>\n' +
                                    '<div class="chat-img"><img src="' + $assetURL + '/' + v.relative_path + '/' + v.image + '" alt="user" /></div>\n' +
                                    '<div class="chat-content">\n' +
                                    '<h5>'+v.name+'</h5>\n' +
                                    '<div class="box bg-light-info chat-img-holder"><a class="post_image" href="' + $assetURL + '/' + $relativePathPost + '/' + v[$colum] + '"><img src="' + $assetURL + '/' + $relativePathPost + '/' + v[$colum] + '" alt="user"></a></div>\n' +
                                    '</div>\n' +
                                    '<div class="chat-time">'+v.created_at+'</div>\n' +
                                    '</li>';
                            }
                        }
                    }
                });
                $('#post-chat').html($html);
                $(".post_image").colorbox({rel:'post_image',maxHeight: 700,
                    maxWidth: 700});
            },
            error: function ($error) {
                notificationMsg($error, error);
            }
        });
    };

    var firstMessageImage = function () {

    }

    // rendering grid
    if ($type.indexOf('render') !== -1) {
        callGridRender();
    } else if ($type.indexOf('delete') !== -1) {
        destroy();
    } else if($type.indexOf('addRecord') !== -1) {
        addRecord();
    } else if($type.indexOf('viewPopup') !== -1) {
        viewPopup();
    }


    var functionList = {};
    functionList['uploadImage'] = uploadImage;
    functionList['uploadPdf'] = uploadPdf;
    functionList["sorter"] = sorter;
    functionList["communityUploader"] = communityUploader;
    functionList["companiesDropDown"] = companiesDropDown;
    functionList["communitiesDropDown"] = communitiesDropDown;
    functionList["joinCommunityAction"] = joinCommunityAction;
    functionList["getCommunitiesOrUsers"] = getCommunitiesOrUsers;
    functionList["reportPostMessage"] = reportPostMessage;
    functionList["sendReportPostMessage"] = sendReportPostMessage;
    functionList["sendPostMessage"] = sendPostMessage;
    functionList["renderPostChats"] = renderPostChats;
    if ($type in functionList) {
        functionList[$type]();
    }

}