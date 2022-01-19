const $ = document.querySelector.bind(document);
const $$ = document.querySelectorAll.bind(document);


function Validator(formSelector,formType) {
    var formRules = {}

    /* 
        *Quy ước tạo rule:
        * - Nếu có lỗi thì return 'error Message'
        * - Nếu ko lỗi thì return undefined 
    */

    var validatorRules = {
        required: function(value) {
            return value.trim() ? undefined : 'Vui lòng nhập trường này!'
        },
        email: function(value) {
            const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(value) ? undefined : "Vui lòng nhập email của bạn!"
        },
        min: function(min) {
            return function(value) {
                return value.length >= min ? undefined : `Vui lòng nhập mật khẩu ít nhất ${min} ký tự!`
            }
        },
        max: function(max) {
            return function(value) {
                return value.length <= max ? undefined : `Vui lòng nhập trường nhiều nhất ${max} ký tự!`
            }
        },
        minDate: function(min) {
            return function(value) {
                return new Date(getMinDay(min)) < new Date(value) ? undefined : `Ngày bắt đầu nghỉ phải sau ${min} ngày nộp đơn!`
            }
        },
        afterDate: function(value) {
            return new Date(getToDay()) <= new Date(value) ? undefined : `Hạn nộp phải được thiết lập trong ngày hoặc sau hôm nay!`
        }
    }
    //Lấy ra form element trong DOM theo Selector
    var formElement = $(formSelector)
    //Chỉ xử lý khi có form element tồn tại
    if(formElement) {
        
        var inputs = formElement.querySelectorAll('[name][rules]')
        
        for(let input of inputs) {
            let rules = input.getAttribute('rules').split('|')
            for(let rule of rules) {
                let ruleFunc = validatorRules[rule]

                if (rule.includes(':')) {
                    let ruleInfo = rule.split(':')
                    ruleFunc = validatorRules[ruleInfo[0]](ruleInfo[1])
                }

                if(Array.isArray(formRules[input.name])) {
                    formRules[input.name].push(ruleFunc)
                } else {
                    formRules[input.name] = [ruleFunc]
                }
            }

            //Lắng nghe sự kiện để validate (blur, change, ...)
            input.onblur = handleValidate
            input.oninput = handleClearError
        }
        
        // Hàm thực hiện validate
        function handleValidate(event) {
            let rules = formRules[event.target.name]
            let errorMessage
            
            rules.some(function (rule) {
                errorMessage = rule(event.target.value)
                return errorMessage
            });

            if (errorMessage) {
                let formGroup = getParent(event.target,'.form-group')
                if(formGroup) {
                    event.target.classList.add('invalid')
                    let formMessage = formGroup.querySelector('.error-message')
                    if(formMessage) {
                        formMessage.innerText = errorMessage
                    }
                }
            }

            return !errorMessage
        }

        // Hàm clear error
        function handleClearError(event) {
            let formGroup = getParent(event.target,'.form-group')
            if(event.target.classList.contains('invalid')) {
                event.target.classList.remove('invalid')

                let formMessage = formGroup.querySelector('.error-message')
                if(formMessage) {
                    formMessage.innerText = ''
                }
            }
        }

        // Xử lý hành vi submit form
        formElement.onsubmit = (event) => {
            event.preventDefault()

            let isValid = true
            for(let input of inputs) {
                if(!handleValidate({target: input})) {
                    isValid = false;
                }
            }
            
            if(isValid) {
                switch (formType) {
                    case 'login':
                        handleLogin(inputs[0].value,inputs[1].value)
                        break
                    case 'resetpassword':
                        handleResetPassword(inputs[0].value,inputs[1].value)
                        break
                    case 'createemployee':
                        handleCreateEmployee(...inputs)
                        break
                    case 'createform':
                        handleCreateForm(...inputs)
                        break
                    case 'changepassword':
                        handleChangePassword(...inputs)
                        break
                    case 'createbranch':
                        handleCreateBranch(...inputs)
                        break
                    case 'updatebranch':
                        handleUpdateBranch(...inputs,formElement)
                        break
                    case 'appointbranch':
                        handleAppoint(...inputs,formElement)
                        break
                    case 'createtask':
                        handleCreateTask(...inputs)
                        break
                    case 'rejecttask':
                        handleRejectTask(...inputs,formElement)
                        break
                    case 'approvetask':
                        handleApproveTask(...inputs,formElement)
                        break
                    case 'submittask':
                        handleSubmitTask(...inputs,formElement)
                        break
                    default:
                        location.href = "http://localhost:8080/error.php"
                }
            }
        }

    }
}

// Lấy element parent
function getParent(element, selector) {
    while(element.parentElement) {
        if(element.parentElement.matches(selector)) {
            return element.parentElement
        }
        element = element.parentElement
    }
}

function handleLogin(username,password) {
    const account = new URLSearchParams({
        'username': username,
        'password': password,
    })

    fetch('/action/login.php', {
        'method': 'POST',
        'body': account
    }).then(res => res.json())
    .then(res => {
        if(res.code === 0) {
            let account = res.data
            if(account.first_time === 1) {
                location.href = 'reset-password.php'
            }
            else {
                decentralizationAccount(account.account_type)
            }
        }
        else {
            hanldeResponse('.form-message',res.message,"alert-danger")
        }
    })
}

function hanldeResponse(messageSelector,message,classAlert) {
    const formMessage = $(messageSelector)

    if(formMessage.classList.contains('alert-danger')) {
        formMessage.classList.remove('alert-danger')
    }
    if(formMessage.classList.contains('alert-success')) {
        formMessage.classList.remove('alert-success')
    }
    if(formMessage.classList.contains('d-none')) {
        formMessage.classList.remove('d-none')
    }
    formMessage.classList.add(classAlert)
    formMessage.innerText = message
}


function decentralizationAccount(type) {
    switch(type) {
        case 1:
            location.href = 'employee/home'
            break
        case 2:
            location.href = 'manager/home'
            break
        case 3:
            location.href = 'director/home'
            break
        default:
            location.href = 'error.php'
            break
    }
}

function handleResetPassword(password,confirmPassword) {
    const newPassword = new URLSearchParams({
        'password': password,
        'confirm_password': confirmPassword,
    })

    fetch('/action/reset-password.php', {
        'method': 'POST',
        'body': newPassword
    }).then(res => res.json())
    .then(res => {
        if(res.code === 0) {
            let type = res.data
            decentralizationAccount(type)
        }
        else {
            hanldeResponse('.form-message',res.message,"alert-danger")
        }
    })
}

function handleResetPassword2(accountID) {
    const resetPassword = new URLSearchParams({
        'account_id': accountID
    })

    fetch('/action/reset-password2.php', {
        'method': 'POST',
        'body': resetPassword
    }).then(res => res.json())
    .then(res => {
        if(res.code === 0) {
            hanldeResponse('.form-message',res.message,"alert-success")
        }
        else {
            hanldeResponse('.form-message',res.message,"alert-danger")
        }
    })
}

function handleLogOutForm(formSelector) {
    const formElement = $(formSelector)
    formElement.onsubmit = (event) => {
        event.preventDefault()
        handleLogOut()
    }
}

function handleLogOut() {
    const message = new URLSearchParams({
        'logout': 'LOA'
    })

    fetch('/action/logout.php', {
        'method': 'POST',
        'body': message
    }).then(res => res.json())
    .then(res => {
        if(res.code === 0) {
            location.href = "/"
        } else {
            location.href = "error.php"
        }
    })
}

function handleCreateEmployee(username,lastname,firstname,identity,gender,
    birthday,email,phone,address,branch,position,salary,startday) {

    const employee = new URLSearchParams({
        'username': username.value,
        'firstname': firstname.value,
        'lastname': lastname.value,
        'identity': identity.value,
        'gender': gender.value,
        'birthday': birthday.value,
        'email': email.value,
        'phone': phone.value,
        'address': address.value,
        'branch': branch.value,
        'position': position.value,
        'salary': salary.value,
        'startday': startday.value,
    })

    fetch('/action/create-employee.php', {
        'method': 'POST',
        'body': employee
    }).then(res => res.json())
    .then(res => {
        if(res.code === 0) {
            hanldeResponse('.form-message',res.message,"alert-success")
            username.value = "";
            firstname.value = "";
            lastname.value = "";
            identity.value = "";
            gender.value = "";
            birthday.value = "";
            email.value = "";
            phone.value = "";
            address.value = "";
            branch.value = "";
            position.value = "";
            salary.value = "";
            startday.value = "";
        } else {
            hanldeResponse('.form-message',res.message,"alert-danger")
        }
    })
}

function handleChangeImage(inputSelector,imageSelector) {
    let file = $(inputSelector).files
    if(file.length > 0) {
        let fileReader = new FileReader()

        fileReader.onload = (e) => {
            $(imageSelector).setAttribute("src",e.target.result)
        }

        fileReader.readAsDataURL(file[0])
    }
}

function handleChangeFile(inputSelector,labelSelector) {
    let file = $(inputSelector).files
    if(file.length > 0) {
        let label = $(labelSelector)
        label.innerText = file[0].name
    }
}

function handleChangeFile2(inputElement,ulSelector) {
    let files = inputElement.files
    let ulElement = $(ulSelector)

    if(files.length == 0) {
        ulElement.innerHTML = ""
    } else {
        ulElement.innerHTML = ""
        for(let i = 0; i < files.length; i++) {
            let name = files[i].name
            let blob = new Blob([files[i]],{type: files[i].type})
            let url = window.URL.createObjectURL(blob)
            let li = document.createElement("li")
            li.classList.add('tasks-create__file-item')
            li.innerHTML = `<a download="${name}" href="${url}" >${name}</a> \n
                            <i onclick="handleDeleteFile(this)" class="fas fa-times"></i>`
            
            ulElement.appendChild(li)
        }
    }
}

function handleDeleteFile(e) {
    let li = getParent(e,'.tasks-create__file-item')
    li.classList.add('d-none')
}

function handleUpdateProfile(inputSelector,formSelector) {
    let form = $(formSelector)
    if(form) {
        form.onsubmit = (event) => {
            event.preventDefault()
            let files = $(inputSelector).files;
            if(files.length > 0) {
                const formData = new FormData()
                formData.append('avatar', files[0])
        
                fetch("/action/update-profile.php", {
                    method: 'POST',
                    body: formData
                }).then(res => res.json())
                .then(res => {
                    if(res.code === 0) {
                        hanldeResponse('.form-message',res.message,"alert-success")
                    } else {
                        hanldeResponse('.form-message',res.message,"alert-danger")
                    }
                })
            }
        }
    }
}

function handleDetail(e) {
    let id = e.getAttribute("id")
    location.href = `detail.php?id=${id}`
}

// Thiết lập ngày bắt đầu nghỉ phải sau ngày nộp đơn ít nhất 3 ngày
function getMinDay(n) {
    var today = new Date();
    today.setDate(today.getDate());
    var dd = today.getDate() + parseInt(n);
    var mm = today.getMonth() + 1; //January is 0 so need to add 1 to make it 1!
    var yyyy = today.getFullYear();
    if (dd < 10) {
        dd = '0' + dd
    }
    if (mm < 10) {
        mm = '0' + mm
    }

    return today = yyyy + '-' + mm + '-' + dd;
}

function handleCreateForm(startDate,numberDate,reason) {
    let file = $('.custom-file-input').files
    const formData = new FormData()
    formData.append('start_day',startDate.value)
    formData.append('number_day',numberDate.value)
    formData.append('reason',reason.value)
    let valid = true
    if(file.length > 0) {
        let disallowance = /(\.sh|\.exe|\.msi|\.msc|\.jar|\.pif)$/i

        if(disallowance.exec($('.custom-file-input').value)) {
            hanldeResponse('.form-message',"Hệ thống không hỗ trợ loại file thực thi!","alert-danger")
            valid = false
        }
        else if(file[0].size > 2 * 1024 * 1024) {
            const size = Math.round(file[0].size / (1024 * 1024))
            hanldeResponse('.form-message',`File của bạn (${size}MB) vượt quá 2MB`,"alert-danger")
            valid = false
        }
        else {
            formData.append('file', file[0])
        }
    }
    if(valid) {
        fetch("/action/create-form.php", {
            method: 'POST',
            body: formData
        }).then(res => res.json())
        .then(res => {
            if(res.code === 0) {
                startDate.value = ""
                numberDate.value = ""
                reason.value = ""
                $('.custom-file-label').innerText = "Choose file..."
                hanldeResponse('.form-message',res.message,"alert-success")
            } else {
                hanldeResponse('.form-message',res.message,"alert-danger")
            }
        })
    }
}

function handleShowHide(e) {
    let x = e.closest('.input-group').querySelector('input');
    console.log(x);
    let show_eye = e.querySelector("#show_eye");
    let hide_eye = e.querySelector("#hide_eye");
    hide_eye.classList.remove("d-none");
    if (x.type === "password") {
        x.type = "text";
        show_eye.style.display = "none";
        hide_eye.style.display = "block";
    } else {
        x.type = "password";
        show_eye.style.display = "block";
        hide_eye.style.display = "none";
    }
}

function handleChangePassword(oldPassword,newPassword,confirmPassword) {
    const changePassword = new URLSearchParams({
        'old_password': oldPassword.value,
        'password': newPassword.value,
        'confirm_password': confirmPassword.value,
    })

    fetch('/action/change-password.php', {
        'method': 'POST',
        'body': changePassword
    }).then(res => res.json())
    .then(res => {
        if(res.code === 0) {
            oldPassword.value = ""
            newPassword.value = ""
            confirmPassword.value = ""
            hanldeResponse('.form-message',res.message,"alert-success")
        }
        else {
            hanldeResponse('.form-message',res.message,"alert-danger")
        }
    })
}

function handleCreateBranch(name,branchID,room,desc) {
    const new_branch = new URLSearchParams({
        'name': name.value,
        'branch_id': branchID.value,
        'room': room.value,
        'desc': desc.value
    })

    fetch('/action/create-branch.php', {
        'method': 'POST',
        'body': new_branch
    }).then(res => res.json())
    .then(res => {
        if(res.code === 0) {
            name.value = ""
            branchID.value = ""
            room.value = ""
            desc.value = ""
            hanldeResponse('.form-message',res.message,"alert-success")
        }
        else {
            hanldeResponse('.form-message',res.message,"alert-danger")
        }
    })
}

function handleApproveForm(id) {
    const approve_form = new URLSearchParams({
        status: 2,
        form_id: id
    })
    console.log(id);
    fetch('/action/update-form.php', {
        'method': 'POST',
        'body': approve_form
    }).then(res => res.json())
    .then(res => {
        if(res.code === 0) {
            location.reload()
            hanldeResponse('.form-message',res.message,"alert-success")
        }
        else {
            hanldeResponse('.form-message',res.message,"alert-danger")
        }
    })
}

function handleRejectForm(id) {
    const reject_form = new URLSearchParams({
        status: 3,
        form_id: id
    })

    fetch('/action/update-form.php', {
        'method': 'POST',
        'body': reject_form
    }).then(res => res.json())
    .then(res => {
        if(res.code === 0) {
            location.reload()
            hanldeResponse('.form-message',res.message,"alert-success")
        }
        else {
            hanldeResponse('.form-message',res.message,"alert-danger")
        }
    })
}

function handleYourForms() {
    location.href = 'yourform.php'
}
function getToDay() {

    let today = new Date();
    let dd = String(today.getDate()).padStart(2, '0');
    let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    let yyyy = today.getFullYear();

    today = mm + '/' + dd + '/' + yyyy;
    return today;
}

function handleCreateTask(name,excutant_id,end_day,desc) {
    const newTask = new FormData()
    let end = end_day.value.replace("T"," ")
    newTask.append('name',name.value)
    newTask.append('excutant_id',excutant_id.value)
    newTask.append('end_day',end)
    newTask.append('desc',desc.value)
    
    let files = $('#tasks-create-file').files
    let arrayLi = $$('.tasks-create__file-item')
    let valid = true;
    let countFiles = 0

    if(files.length > 0) {
        for(let i = 0; i < arrayLi.length; i++) {
            if(!arrayLi[i].classList.contains('d-none')) {
                if(validateFile(files[i],'.form-message')) {
                    newTask.append(`file${countFiles}`,files[i])
                    countFiles++
                } else {
                    valid = false
                }
            }
        }
    }

    if(valid) {
        newTask.append('count_files',countFiles)
        fetch("/action/create-task.php", {
            method: 'POST',
            body: newTask
        }).then(res => res.json())
        .then(res => {
            if(res.code === 0) {
                name.value = ""
                excutant_id.value = ""
                end_day.value = ""
                desc.value = ""
                arrayLi.innerHTML = ""
                hanldeResponse('.form-message',res.message,"alert-success")
            } else if(res.code === 1) {
                hanldeResponse('.form-message',res.message,"alert-danger")
            } else {
                location.href = "http://localhost:8080/error.php"
            }
        })
    }

}

function validateFile(file,messageSelector) {
    let disallowance = /(\.sh|\.exe|\.msi|\.msc|\.jar|\.pif)$/i

    if(disallowance.exec(file.name)) {
        hanldeResponse(messageSelector,"Hệ thống không hỗ trợ loại file thực thi!","alert-danger")
        return false
    }
    else if(file.size > 2 * 1024 * 1024) {
        let size = Math.round(file.size / (1024 * 1024))
        hanldeResponse(messageSelector,`File của bạn (${size}MB) vượt quá 2MB`,"alert-danger")
        return false
    }
    else {
        return true
    }
}

function handleCancelTask(id) {
    const cancelTask = new URLSearchParams({
        status: 4,
        task_id: id
    })

    fetch("/action/update-task.php", {
        method: 'POST',
        body: cancelTask
    }).then(res => res.json())
    .then(res => {
        if(res.code === 0) {
            location.reload()
        } else {
            hanldeResponse('.form-message-cancel',res.message,"alert-danger")
        }
    })
}

function handleRejectTask(reason,formElement) {
    const rejectTask = new FormData()
    rejectTask.append('reason',reason.value)
    rejectTask.append('status',5)
    rejectTask.append('task_id',formElement.getAttribute("id"))
    const files = $('#reject-file').files
    const deadline = $('#reject-day').value

    let arrayLi = $$('.tasks-create__file-item')
    let valid = true
    let countFiles = 0

    if(deadline) {
        if(new Date(getToDay()) > new Date(deadline)) {
           let message = `Hạn nộp phải được thiết lập trong ngày hoặc sau hôm nay!`
            hanldeResponse('.form-message-reject',message,"alert-danger")
            valid = false
        } else {
            rejectTask.append('new_deadline',deadline)
        }
    }

    if(files.length > 0) {
        for(let i = 0; i < arrayLi.length; i++) {
            if(!arrayLi[i].classList.contains('d-none')) {
                if(validateFile(files[i]),'.form-message-reject') {
                    rejectTask.append(`file${countFiles}`,files[i])
                    countFiles++
                } else {
                    valid = false
                }
            }
        }
    }

    if(valid) {
        rejectTask.append('count_files',countFiles)
        fetch("/action/update-task.php", {
            method: 'POST',
            body: rejectTask
        }).then(res => res.json())
        .then(res => {
            if(res.code === 0) {
                location.reload()
            } else if(res.code === 2) {
                location.href = "http://localhost:8080/error.php"
            }
            else {
                hanldeResponse('.form-message-reject',res.message,"alert-danger")
            }
        })
    }
}

function handleApproveTask(rate,formElement) {
    const approveTask = new URLSearchParams({
        status: 6,
        task_id: formElement.getAttribute("id"),
        rate: rate.value
    })

    fetch("/action/update-task.php", {
        method: 'POST',
        body: approveTask
    }).then(res => res.json())
    .then(res => {
        if(res.code === 0) {
            location.reload()
        } else if(res.code === 2) {
            location.href = "http://localhost:8080/error.php"
        } else {
            hanldeResponse('.form-message-reject',res.message,"alert-danger")
        }
    })
}

function handleStartTask(id) {
    const startTask = new URLSearchParams({
        status: 2,
        task_id: id
    })

    fetch("/action/update-task.php", {
        method: 'POST',
        body: startTask
    }).then(res => res.json())
    .then(res => {
        if(res.code === 0) {
            location.reload()
        } else {
            hanldeResponse('.form-message-start',res.message,"alert-danger")
        }
    })
}

function handleSubmitTask(submitText,formElement) {
    const files = $('#submitFile').files
    let arrayLi = $$('.tasks-create__file-item')
    let valid = true
    let countFiles = 0
    
    const startTask = new FormData()
    startTask.append('message',submitText.value)
    startTask.append('status',3)
    startTask.append('task_id',formElement.getAttribute("id"))

    if(files.length > 0) {
        for(let i = 0; i < arrayLi.length; i++) {
            if(!arrayLi[i].classList.contains('d-none')) {
                if(validateFile(files[i]),'.form-message') {
                    startTask.append(`file${countFiles}`,files[i])
                    countFiles++
                } else {
                    valid = false
                }
            }
        }
    } else {
        hanldeResponse('.form-message',"Vui lòng chọn file để submit task","alert-danger")
    }
    
    if(valid) {
        startTask.append('count_files',countFiles)
        fetch("/action/update-task.php", {
            method: 'POST',
            body: startTask
        }).then(res => res.json())
        .then(res => {
            if(res.code === 0) {
                location.reload()
            } else {
                hanldeResponse('.form-message',res.message,"alert-danger")
            }
        })
    }
}

function handleReload() {
    location.reload()
}

function handleLoadMore(btnElement,table,selector,conf) {
    let cards = $$(selector)
    let itemsSubNavPC = $$('.list-group-item')

    const data = new URLSearchParams({
        table: table,
        number: cards.length,
        conf: conf
    })

    if(itemsSubNavPC) {
        itemsSubNavPC.forEach((item) => {
            if(item.classList.contains('bg-primary','text-white')) {
                data.append("data",item.getAttribute('id').slice(0,2))
                switch (table) {
                    case 'employee':
                        data.append('column','branch_id')
                        break
                    case 'form':
                        data.append('column','status')
                        break
                    case 'task':
                        data.append('column','status')
                        break
                    default:
                        break
                }
            }
        })
    }

    const xhr = new XMLHttpRequest()

    xhr.addEventListener('load',(e) => {
        if(xhr.readyState === 4 && xhr.status === 200) {
            const result = JSON.parse(xhr.responseText)
            if(result.code == 0) {
                switch (table) {
                    case 'employee':
                        addCardEmployees(result.data[0])
                        break
                    case 'form':
                        addCardForms(result.data[0])
                        break
                    case 'task':
                        addCardTasks(result.data[0])
                        break
                    default:
                        break
                }
                if(btnElement.classList.contains('d-flex')) {
                    btnElement.classList.remove('d-flex')
                }
                if(btnElement.classList.contains('d-none')) {
                    btnElement.classList.remove('d-none')
                }
                btnElement.classList.add(result.data[1])
                
            } else {
                location.href = "http://localhost:8080/error.php"
            }
        }
        else {
            location.href = "http://localhost:8080/error.php"
        }
    })
    xhr.open('POST','/action/get-more.php',true)
    xhr.send(data)
}

function addCardEmployees(employees) {
    let rowElement = $('.row-employee')
    if(rowElement) {
        let htmlEmployees = employees.map((employee) => {
            return `
                <div id='${employee.emp_id}' class='card' onclick='handleDetail(this)'>
                    <img class='card-img-top' src='${employee.avatar}' alt='Avatar'>
                    <div class='card-body card-shadow'>
                        <h5 class='card-title card-text-nowrap'>${employee.last_name} ${employee.first_name}</h5>
                        <p class='card-text card-text1-nowrap'>Phòng: ${employee.branch_id}</p>
                        <p class='card-text card-text1-nowrap'>${employee.position}</p>
                    </div>
                </div>
            `
        })

        htmlEmployees.forEach((html) => {
            let div = document.createElement('div')
            div.classList.add('col-6','col-sm-6','col-md-4', 'col-lg-4', 'pb-4')
            div.innerHTML = html
            rowElement.appendChild(div)
        })
    }
}

function addCardForms(forms) {
    let rowElement = $('.row-form')
    if(rowElement) {
        let htmlForms = forms.map((form) => {
            return `
                <div id='${form.form_id}' class='card ${form.status[1]}' onclick='handleDetail(this)'>
                    <div class='card-body'>
                        <h4 class='card-title ${form.status[0]}'>Đơn xin nghỉ</h4>
                        <h6 class='card-subtitle mb-2 ${form.status[0]}'>Ngày gửi: ${form.submit_day}</h6>
                        <p class='card-text ${form.status[0]} card-text-nowrap'>Đơn của ${form.emp_id}</p>
                        <p class='card-text ${form.status[0]}'>Trạng thái: <strong>${form.status[2]}</strong></p>
                    </div>
                </div>
            `
        })

        htmlForms.forEach((html) => {
            let div = document.createElement('div')
            div.classList.add('col-12','col-sm-6','col-md-4', 'col-lg-4', 'mb-4')
            div.innerHTML = html
            rowElement.appendChild(div)
        })
    }
}
function addCardTasks(tasks) {
    let rowElement = $('.row-task')
    if(rowElement) {
        let htmlTasks = tasks.map((task) => {
            return `
                <div id='${task.task_id}' class='card ${task.status[1]}' onclick='handleDetail(this)'>
                    <div class='card-body'>
                        <h4 class='card-title ${task.status[0]} card-title-nowrap'>${task.name}</h4>
                        <h6 class='card-subtitle mb-2 ${task.status[0]}'>Ngày giao: ${task.start_day}</h6>
                        <p class='card-text ${task.status[0]} card-text-nowrap'>Giao cho: ${task.executant_id}</p>
                        <p class='card-text ${task.status[0]}'>Trạng thái: <strong>${task.status[2]}</strong></p>
                    </div>
                </div>
            `
        })

        htmlTasks.forEach((html) => {
            let div = document.createElement('div')
            div.classList.add('col-12','col-sm-6','col-md-4', 'col-lg-4', 'mb-4')
            div.innerHTML = html
            rowElement.appendChild(div)
        })
    }
}

function handleRender(e,table = "employee") {
    let itemsSubNavPC = $$('.list-group-item')
    let itemsSubNavMobile = $$('.dropdown-item-subnav')
    let btnDropdown = $('#dropdownMenu1')
    const [id,cof] =  e.getAttribute('id').split(" ")

    if(itemsSubNavPC && itemsSubNavMobile && btnDropdown) {
        itemsSubNavPC.forEach((item) => {
            if(item.classList.contains('bg-primary','text-white')) {
                item.classList.remove('bg-primary','text-white')
            }
            if(item.getAttribute('id').includes(id)) {
                item.classList.add('bg-primary', 'text-white')
            }
        })

        itemsSubNavMobile.forEach((item) => {
            if(item.classList.contains('bg-primary','text-white')) {
                item.classList.remove('bg-primary','text-white')
            }
            if(item.getAttribute('id').includes(id)) {
                item.classList.add('bg-primary', 'text-white')
            }
        })

        btnDropdown.innerHTML = e.innerText + "<i class='fas fa-chevron-down icon-dropdown mt-2'></i>"
        handleLoad(id,table,cof)
    }
}

function handleLoad(id,table,cof) {
    const data = new URLSearchParams({
        table: table,
        number: 0,
        data: id,
    })

    if(cof) {
        data.append('conf',cof)
    }

    switch (table) {
        case 'employee':
            data.append('column','branch_id')
            break
        case 'form':
            data.append('column','status')
            break
        case 'task':
            data.append('column','status')
            break
        default:
            break
    }
    let btnElement = $('.more-btn')
    const xhr = new XMLHttpRequest()

    xhr.addEventListener('load',(e) => {
        if(xhr.readyState === 4 && xhr.status === 200) {
            const result = JSON.parse(xhr.responseText)
            if(result.code == 0) {
                switch (table) {
                    case 'employee':
                        renderCardEmployees(result.data[0])
                        break
                    case 'form':
                        renderCardForms(result.data[0])
                        break
                    case 'task':
                        renderCardTasks(result.data[0])
                        break
                    default:
                        break
                }
                if(result.data[1] && btnElement) {
                    if(btnElement.classList.contains('d-flex')) {
                        btnElement.classList.remove('d-flex')
                    }
                    btnElement.classList.add(result.data[1])
                }

            } else {
                location.href = "http://localhost:8080/error.php"
            }
        }
        else {
            location.href = "http://localhost:8080/error.php"
        }
    })
    xhr.open('POST','/action/get-more.php',true)
    xhr.send(data)
}

function renderCardEmployees(employees) {
    let rowElement = $('.row-employee')
    if(rowElement && employees) {
        let htmlEmployees = employees.map((employee) => {
            return `
                <div class='col-6 col-sm-6 col-md-4 col-lg-4 pb-4'>
                    <div id='${employee.emp_id}' class='card' onclick='handleDetail(this)'>
                        <img class='card-img-top' src='${employee.avatar}' alt='Avatar'>
                        <div class='card-body card-shadow'>
                            <h5 class='card-title card-text-nowrap'>${employee.last_name} ${employee.first_name}</h5>
                            <p class='card-text card-text1-nowrap'>Phòng: ${employee.branch_id}</p>
                            <p class='card-text card-text1-nowrap'>${employee.position}</p>
                        </div>
                    </div>
                </div>
            `
        })

        rowElement.innerHTML = htmlEmployees.join('')
    }
}
function renderCardForms(forms) {
    let rowElement = $('.row-form')
    if(rowElement) {
        if(forms) {
            let htmlForms = forms.map((form) => {
                return `
                    <div class='col-12 col-sm-6 col-md-4 col-lg-4 mb-4'>
                        <div id='${form.form_id}' class='card ${form.status[1]}' onclick='handleDetail(this)'>
                            <div class='card-body'>
                                <h4 class='card-title ${form.status[0]}'>Đơn xin nghỉ</h4>
                                <h6 class='card-subtitle mb-2 ${form.status[0]}'>Ngày gửi: ${form.submit_day}</h6>
                                <p class='card-text ${form.status[0]} card-text-nowrap'>Đơn của ${form.emp_id}</p>
                                <p class='card-text ${form.status[0]}'>Trạng thái: <strong>${form.status[2]}</strong></p>
                            </div>
                        </div>
                    </div>
                `
            })
            rowElement.innerHTML = htmlForms.join('')
        } else {
            rowElement.innerHTML = ""
        }
    }
}

function renderCardTasks(tasks) {
    let rowElement = $('.row-task')
    if(rowElement) {
        if(tasks) {
            let htmlTasks = tasks.map((task) => {
                return `
                    <div class='col-12 col-sm-6 col-md-4 col-lg-4 mb-4'>
                        <div id='${task.task_id}' class='card ${task.status[1]}' onclick='handleDetail(this)'>
                            <div class='card-body'>
                                <h4 class='card-title ${task.status[0]} card-title-nowrap'>${task.name}</h4>
                                <h6 class='card-subtitle mb-2 ${task.status[0]}'>Ngày giao: ${task.start_day}</h6>
                                <p class='card-text ${task.status[0]} card-text-nowrap'>Giao cho: ${task.executant_id}</p>
                                <p class='card-text ${task.status[0]}'>Trạng thái: <strong>${task.status[2]}</strong></p>
                            </div>
                        </div>
                    </div>
                `
            })
            rowElement.innerHTML = htmlTasks.join('')
        } else {
            rowElement.innerHTML = ""
        }
    }
}

function handleUpdateBranch(name,id,desc,formElement) {
    const data = new URLSearchParams({
        branch_id: formElement.getAttribute('id'),
        name: name.value,
        id: id.value,
        desc: desc.value,
    })
    
    fetch("/action/update-branch.php", {
        method: 'POST',
        body: data
    }).then(res => res.json())
    .then(res => {
        if(res.code === 0) {
            location.href = `http://localhost:8080/director/branch/detail.php?id=${res.data}`
        } else if(res.code === 2) {
            location.href = "http://localhost:8080/error.php"
        }
        else {
            hanldeResponse('.form-message',res.message,"alert-danger")
        }
    })
}

function handleAppoint(nv,formElement) {
    const new_manager = new URLSearchParams({
        branch_id: formElement.getAttribute('id'),
        emp_id: nv.value,
    })
    
    fetch("/action/appoint-manager.php", {
        method: 'POST',
        body: new_manager
    }).then(res => res.json())
    .then(res => {
        if(res.code === 0) {
            location.reload()
        } else {
            hanldeResponse('.form-message',res.message,"alert-danger")
        }
    })
}