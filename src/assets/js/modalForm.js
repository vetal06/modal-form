(function( $ ){
    $.fn.vskModalForm = function(modalId) {
        var element = $(this);
        var VskModalForm = {
            element: element,
            modalId : modalId,
            init: function () {
                element.trigger('vskModalForm:init', this);
                this.setClickEventLising();
                return this;
            },

            setClickEventLising: function () {
                var _t = this;
                var enableSubmit = true;
                element.on('click', function () {
                    event.preventDefault();
                    if (!enableSubmit) { //эта штука для блокировки даблкликов и других trigger submit
                        return false
                    }
                    enableSubmit = false;
                    setTimeout(function(){
                        enableSubmit = true;
                    }, 1000);
                    var button = $(this),
                        url = button.data('modal-url');
                    if ($('#'+_t.modalId).length === 0) {
                        $('body').append(_t.modalTempldate());
                    }
                    var modal = $('#'+_t.modalId);
                    modal.data('url', url);
                    modal.data('trigger', $(this));
                    $.ajax({
                        url: url,
                        success: function (data) {
                            _t.registerModalData(modal, data);
                            modal.modal('show');
                            element.trigger('vskModalForm:showModal', this);
                        }, 
                        error: function (data) {
                            alert(data.statusText);
                        }
                    });
                });
            },

            /**
             * регистрация данных и скриптов в модальном окне
             * @param modal
             * @param data
             */
            registerModalData: function (modal, data) {
                if (!!data.modal.title) {
                    modal.find('.modal-title').html(data.modal.title);
                }
                if (!!data.modal.body) {
                    modal.find('.modal-body').html(data.modal.body);
                }
                var needTimeout = false;
                if (!!data.jsFiles) {
                    $.each(data.jsFiles, function(k, jsList){
                        $.each(jsList, function (jsKey, jsString) {
                            if ($("html script[src='"+jsKey+"']").length === 0) {
                                var f = document.getElementsByTagName('script')[0],
                                    j = document.createElement('script');
                                j.async = true;
                                j.src = jsKey;
                                f.parentNode.insertBefore(j,f);
                                needTimeout = true;
                            }
                        })
                    });
                }
                if (!!data.cssFiles) {
                    var head = $('head');
                    $.each(data.cssFiles, function(k, cssFile) {
                        if ($('link[href="'+k+'"]').length === 0) {
                            head.append(cssFile);
                        }

                    });
                }
                if (!!data.js) {
                    var evalJs = function (js) {
                        var allJsString = '';
                        $.each(js, function(k, jsList){
                            $.each(jsList, function (key, jsString) {
                                allJsString += jsString;
                            })
                        });
                        if (allJsString.length > 0) {
                            eval(allJsString);
                        }
                    }
                    if (needTimeout) {
                        setTimeout(function(){ /// этот костыль так как не знаю как отследить динамическую подгрузку файлов
                            evalJs(data.js);
                        }, 500);
                    } else {
                        evalJs(data.js);
                    }

                }
                this.setSubmitAction(modal);
                this.setLinkAction(modal);
            },
            /**
             * Обработка сабмита формы
             * @param form
             * @param modal
             */
            setSubmitAction: function (modal) {
                var _t = this;
                var form = modal.find('form');
                if (form.length == 0) {
                    return false;
                }
                var enableSubmit = true;
                form.on('submit', function (event) {
                    event.preventDefault();
                    if (!enableSubmit) { //эта штука для блокировки даблкликов и других trigger submit
                        return false
                    }
                    enableSubmit = false;
                    setTimeout(function(){
                        enableSubmit = true;
                    }, 1000);

                    var url = $(this).attr('action');
                    var dataForm = $(this).serializeArray().reduce(function(obj, item) {
                        obj[item.name] = item.value;
                        return obj;
                    }, {});
                    var data = new FormData();
                    $.each(dataForm, function (k, v) {
                        data.append(k, v);
                    })
                    $(this).find('input[type="file"]').each(function(k,attribute){
                        var name = $(attribute).attr('name');
                        if (data.has(name)) {
                            data.delete(name);
                        }
                        $.each(attribute.files, function (i, file) { // TODO: не знаю как отправлять много файлов в одном инпуте(
                            data.append(name, file);
                        })
                    })
                    $.ajax({
                        method: 'POST',
                        url: url,
                        data: data,
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            if (!!data.status && data.status == '200') {
                                var triggerButton = modal.data('trigger')
                                if (triggerButton.length > 0) {
                                    var modalTrigger = triggerButton.closest('.modal');
                                    if (modalTrigger.length > 0) {
                                        var pjax = modalTrigger.find('div[data-pjax-container]');
                                        if (pjax.length > 0) {
                                            var url =  modalTrigger.data('url');
                                            $.pjax.reload('#'+pjax.attr('id'), {url:url});
                                        }
                                    }
                                }

                                modal.modal('hide');
                                element.trigger('vskModalForm:formSuccessSubmit', _t);
                            } else if(!!data.modal.body) {
                                _t.registerModalData(modal, data);
                                element.trigger('vskModalForm:formErrorSubmit', _t);
                            }
                        },
                        error: function (data) {
                            alert(data.statusText);
                        }
                    });
                    return false;
                });
            },
            modalTempldate : function () {
                return '<div class="modal fade vskModalForm" id="'+this.modalId+'" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">\n' +
                '  <div class="modal-dialog" role="document">\n' +
                '    <div class="modal-content">\n' +
                '      <div class="modal-header">\n' +
                '        <h5 class="modal-title" id="exampleModalLabel"></h5>\n' +
                '        <button type="button" class="close" data-dismiss="modal" aria-label="Close">\n' +
                '          <span aria-hidden="true">&times;</span>\n' +
                '        </button>\n' +
                '      </div>\n' +
                '      <div class="modal-body">\n' +
                '      </div>\n' +
                '    </div>\n' +
                '  </div>\n' +
                '</div>'
            },
            setLinkAction: function (modal) {
                var _t = this;
                var pjax = modal.find('div[data-pjax-container]');
                pjax.on('pjax:beforeSend', function (event,prop, settings) {
                    event.preventDefault();
                    var modal = $(this).closest('.modal');
                    var url = settings.url;
                    modal.data('url', url);
                    if (!$(this).data('disabled-ajax-link')) {
                        event.preventDefault();
                        $.ajax({
                            url: url,
                            success: function (data) {
                                _t.registerModalData(modal, data);
                            },
                            error: function (data) {
                                alert(data.statusText);
                            }
                        });
                    }
                    return false;
                });
            }
        };

        return VskModalForm;
    };

})( jQuery );