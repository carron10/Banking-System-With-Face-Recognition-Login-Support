class FaceDetector {
    constructor(element = "facial_container") {
        $("#" + element).html(`
        <div id="facial_container2024">
        <video id="video" style="display: none;" autoplay></video>
        <canvas id="videocanvas"></canvas>
        <canvas id="canvas"></canvas>
        <canvas id="canvas34" width="250" height="250"></canvas>
        </div>
    `);

        this.element = element

        navigator.mediaDevices.getUserMedia({
            video: true
        })
            .then(stream => {
                const video = document.querySelector(`#${element} #video`)
                video.srcObject = stream;
                this.video = video
                function drawVideoFrame() {
                    const canvas = document.querySelector(`#${element}  #videocanvas`);
                    const context = canvas.getContext('2d');
                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                    requestAnimationFrame(drawVideoFrame); // Call recursively for smooth animation
                }
                // Start drawing the video frame
                drawVideoFrame();
            })
            .catch(err => {
                console.error('Error accessing webcam:', err);
            });
    }
    login(detector_url, email, register_token_callback) {
        var retrial = 0

       var processimage= setInterval(() => {
            const canvas = document.querySelector(`#${this.element}  #canvas`)
            const context = canvas.getContext('2d');
            canvas.width = this.video.videoWidth;
            canvas.height = this.video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = canvas.toDataURL('image/jpeg');
            context.clearRect(0, 0, canvas.width, canvas.height);

            fetch(detector_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    pic: imageData,
                    email: email
                })
            })
                .then(response => response.json())
                .then(data => {
                    register_token_callback(data.token)
                })
                .catch(error => {
                    retrial += 1

                    if(retrial==10){
                    console.log(error);
                    clearInterval(processimage)
                    swal("Login Failed","Login failed","error")
                    }
                    
                });
        }, 1000)

    }
    register(detector_url, email, register_token_callback, re_register = false, user_exist_error = null) {
        var retrial = 0
        // Send frames from webcam to server
        const captureFrames = () => {
            const canvas = document.querySelector(`#${this.element}  #canvas`)
            const context = canvas.getContext('2d');
            canvas.width = this.video.videoWidth;
            canvas.height = this.video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = canvas.toDataURL('image/jpeg');
            context.clearRect(0, 0, canvas.width, canvas.height);

            fetch(detector_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    pic: imageData,
                    re_register: re_register,
                    email: email
                })
            })
                .then(response => response.json())
                .then(data => {
                    retrial = 0
                    // Clear the canvas
                    // context.clearRect(0, 0, canvas.width, canvas.height);
                    var faceLocation = data.faceLocation
                    const canvas = document.querySelector(`#${this.element}  #canvas`)
                    const ctx = canvas.getContext('2d');
                    ctx.clearRect(0, 0, canvas.width, canvas.height);
                    if (faceLocation) {
                        if (!donescanning) {
                            const [top, right, bottom, left] = faceLocation[0];
                            ctx.strokeStyle = 'red';
                            ctx.lineWidth = 3;
                            ctx.beginPath();
                            ctx.rect(left, top, right - left, bottom - top);
                            ctx.stroke();
                            num_scanned += 1
                            donescanning = num_scanned == imageScanMax
                            if ((num_scanned / imageScanMax) > 0.74) {
                                colors[0] = '#28a745'
                            } else if ((num_scanned / imageScanMax) > 0.5) {
                                colors[0] = "#ffc107"
                            }
                            this.drawProgress(125, 125, 100, 10, [num_scanned, imageScanMax - num_scanned], colors, [], 0);
                        } else {
                            clearInterval(processimage)
                        }
                    } else if (data.register_token) {
                        clearInterval(processimage)
                        register_token_callback(token)
                    }
                })
                .catch(error => {
                    retrial += 1

                    // if(retrial==10){
                    console.log(error);
                    clearInterval(processimage)
                    // }
                    // swal("Failed!", error, 'error')
                });
        };
        var processimage = setInterval(captureFrames, 1000);
        var imageScanMax = 10,
            donescanning = false,
            num_scanned = 0;
        var colors = ['red', 'gray'];
        this.drawProgress(125, 125, 100, 8, [0, 100], colors, [], 0);

    }
    register2(detector_url, email, register_token_callback, re_register = false, user_exist_error = null) {
        var query = {
            email: email
        }
        if (re_register) {
            query['re_register'] = true
        }
        const socket = io.connect(detector_url, {
            path: '/websocket',
            query: query,
            reconnection: true, // Enable reconnection (default: true)
            reconnectionDelay: 1000, // Initial reconnection delay (ms)
            reconnectionDelayMax: 5000, // Maximum reconnection delay (ms)
            reconnectionAttempts: 40, // Number of reconnection attempts (default: Infinity)
        });

        // Send frames from webcam to server
        const captureFrames = () => {
            const canvas = document.querySelector(`#${this.element}  #canvas`)
            const context = canvas.getContext('2d');
            canvas.width = this.video.videoWidth;
            canvas.height = this.video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = canvas.toDataURL('image/jpeg');
            context.clearRect(0, 0, canvas.width, canvas.height);
            socket.emit('image', imageData);
            if (donescanning) {
                clearInterval(processimage)
            }
        };
        var processimage = setInterval(captureFrames, 1000);
        var imageScanMax = 10,
            donescanning = false,
            num_scanned = 0;
        var colors = ['red', 'gray'];
        this.drawProgress(125, 125, 100, 8, [0, 100], colors, [], 0);
        // Receive processed image data from server
        socket.on('faceLocation', data => {
            const canvas = document.querySelector(`#${this.element}  #canvas`)
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            var faceLocation = data.loc, token = data.register_token
            if (token) {
                donescanning = true
                num_scanned = imageScanMax
                colors[0] = '#28a745'

                this.drawProgress(125, 125, 100, 10, [num_scanned, imageScanMax - num_scanned], colors, [], 0);
                clearInterval(processimage)
                register_token_callback(token)
                socket.disconnect();
            }
            if (faceLocation) {
                if (!donescanning) {
                    const [top, right, bottom, left] = faceLocation[0];
                    ctx.strokeStyle = 'red';
                    ctx.lineWidth = 3;
                    ctx.beginPath();
                    ctx.rect(left, top, right - left, bottom - top);
                    ctx.stroke();
                    num_scanned += 1
                    donescanning = num_scanned == imageScanMax
                    if ((num_scanned / imageScanMax) > 0.74) {
                        colors[0] = '#28a745'
                    } else if ((num_scanned / imageScanMax) > 0.5) {
                        colors[0] = "#ffc107"
                    }
                    this.drawProgress(125, 125, 100, 10, [num_scanned, imageScanMax - num_scanned], colors, [], 0);
                } else {
                    clearInterval(processimage)
                }
            }
        });
        socket.on("register_image_sample", (n) => {
            imageScanMax = n
        })
        socket.on("register_token", (token) => {
            // swal("Alert", "TokenReceived  ", "success");
            donescanning = true
            num_scanned = imageScanMax
            register_token_callback(token)
            socket.disconnect();
        })
        socket.on("user_exist_error", (errormsg) => {
            swal("UserExist", errormsg, "error");
            socket.disconnect();
        })
    }
    drawProgress(cx, cy, radius, arcwidth, values, colors, labels, selectedValue) {
        var canvas = document.querySelector(`#${this.element} #canvas34`);
        var ctx = canvas.getContext("2d");
        var tot = 0;
        var accum = 0;
        var PI = Math.PI;
        var PI2 = PI * 2;
        var offset = -PI / 2;
        ctx.lineWidth = arcwidth;
        for (var i = 0; i < values.length; i++) {
            tot += values[i];
        }
        for (var i = 0; i < values.length; i++) {
            ctx.beginPath();
            ctx.arc(cx, cy, radius,
                offset + PI2 * (accum / tot),
                offset + PI2 * ((accum + values[i]) / tot)
            );
            ctx.strokeStyle = colors[i];
            ctx.stroke();
            accum += values[i];
        }
    }
}







var addFormData = (v, d) => {
    if (v[d.name] != undefined) {
        if (typeof v[d.name] == 'string' || typeof v[d.name] == 'number') {
            v[d.name] = [v[d.name], d.type == 'checkbox' ? ($(d).is(":checked") ? 1 : 0) : $(d).val()];

        } else {
            v[d.name].push(d.type == 'checkbox' ? ($(d).is(":checked") ? 1 : 0) : $(d).val());

        }
    } else {
        // console.log(d.name,$(d).val(), $(d).is(":checked"),$(d).val())
        v[d.name] = d.type == 'checkbox' ? ($(d).is(":checked") ? 1 : 0) : $(d).val();
    };
};

function getFormData(id) {

    var inputs = $('#' + id + ' :input');

    var values = {},
        key = $('#' + id).attr('data-cf-formkey');
    inputs.each(function () {
        var skip = $(this).parents('.skip-form').first();
        var sub = $(this).parents('.sub-form').first();
        if (skip.length != 0) {
            if (skip.attr('id') != id && skip.find('#' + id).length == 0) { //chcks if the skipform is not a parent of current form
                return;
            }
        }
        if (sub.length != 0) {
            if (sub.attr('id') != id && sub.find('#' + id).length == 0) {
                values[sub.attr('id')] = getFormData(sub.attr('id'));
                return;
            }
        }
        if ($(this).hasClass('key')) {
            return
        }
        if ($(this).attr('data-cf-setunder') != null) {
            if ($(this).val() != '') {
                if (values[$(this).attr('data-cf-setunder')] == undefined) {
                    values[$(this).attr('data-cf-setunder')] = {};
                }
                if (this.type == 'checkbox') {
                    addFormData(values[$(this).attr('data-cf-setunder')], this);
                } else if ($(this).tagName() == 'SELECT') {
                    if ($(this).val() != -1) {
                        addFormData(values[$(this).attr('data-cf-setunder')], this);
                    }
                } else {
                    addFormData(values[$(this).attr('data-cf-setunder')], this);
                }
            }
        } else if ($(this).val() != '') {
            if ($(this).hasClass('value')) {
                var key = $(this).parents('.key_value').first().find('.key').first().val();
                values[key] = $(this).val();
                return;
            }

            if (this.type == 'checkbox') {
                addFormData(values, this);
            } else if ($(this).tagName() == 'SELECT') {
                if ($(this).val() != -1) {
                    addFormData(values, this);
                }
            } else {
                addFormData(values, this);
            }
        }
    });
    if (key != undefined) {
        var v = {};
        v[key] = values;
        return v;
    }
    return values;
}
jQuery.fn.tagName = function () {
    return this.prop("tagName");
};
function error(str, title) {
    $("#alert").modal("show").find('.modal-content').addClass(
        "bg-danger").removeClass("bg-primary").find(
            '.modal-body').html(
                "<b>" + str + "</b>");
    $('#alert').find('.title').html('<b>' + (title == undefined ? "Error" : title) + '</b>').addClass('text-white');
}
function send(url, data, method) {
    return $.ajax({
        url: url,
        method: method,
        data: data
    });
}
