@extends('vendor.site-bases.admin.inc.layout')

@section('title', 'თარგმნა')

@section('content')
    <div class="page-title">
        <div class="title_left">
            <h3>თარგმნა</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <div class="alert alert-success success-publish" style="display:none;">
                        <p>ინფორმაცია წარმატებით განახლდა!</p>
                    </div>
                    <div class="alert alert-success success-find" style="display:none;">
                        <p>ნაპოვნია <strong class="counter">N</strong> სიტყვა!</p>
                    </div>
                    @if (Session::has('successPublish'))
                        {{ Session::get('successPublish') }}
                    @endif
                    <p>
                        @if (!isset($group))
                            <form class="form-inline form-import" method="POST" action="{{ action('\Barryvdh\TranslationManager\Controller@postImport') }}" data-remote="true" role="form">
                                @csrf
                                <select name="replace" class="form-control">
                                    <option value="0">გაახლება</option>
                                    <option value="1">არსებულების ჩანაცვლება</option>
                                </select>
                                <button type="submit" class="btn btn-success"  data-disable-with="იტვირთება..">იმპორტი</button>
                            </form>
                        @endif
                        @if (isset($group))
                            <form class="form-inline form-publish" method="POST" action="{{ action('\Barryvdh\TranslationManager\Controller@postPublish', $group) }}" data-remote="true" role="form" data-confirm="დარწმუნებული ხართ, რომ გსურთ შენახვა?">
                                @csrf
                                <button type="submit" class="btn btn-info" data-disable-with="Publishing.." >შენახვა</button>
                                <a href="<?= action('\Barryvdh\TranslationManager\Controller@getIndex') ?>" class="btn btn-default">უკან</a>
                            </form>
                        @endif
                    </p>
                    <form role="form">
                        @csrf
                        <div class="form-group">
                            <p style=" margin-bottom:10px">აირჩიეთ სათარგმნი ჯგუფი</p>
                            <select name="group" id="group" class="form-control group-select">
                                @foreach ($groups as $key => $value)
                                    <option value="{{ $key }}" {{ $key == $group ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                    @if ($group)
                        <form action="{{ action('\Barryvdh\TranslationManager\Controller@postAdd', array($group)) }}" method="POST"  role="form">
                            @csrf
                            <input type="text" class="form-control" name="keys">
                            <p></p>
                            <input type="submit" value="დაამატე საკვანძო სიტყვა" class="btn btn-primary">
                        </form>
                        <hr>
                        <h4>სულ: {{ $numTranslations }}, შეცვლილი: {{ $numChanged }}</h4>
                        <table class="table table-striped jambo_table bulk_action">
                            <thead>
                                <tr>
                                    <th width="15%">საკვანძო სიტყვა</th>
                                    @foreach ($locales as $locale)
                                        <th>
                                            {{ Arr::get(LaravelLocalization::getSupportedLocales(), sprintf('%s.native', $locale)) }}
                                        </th>
                                    @endforeach
                                    @if ($deleteEnabled)
                                        <th></th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($translations as $key => $translation)
                                    <tr id="{{ $key }}">
                                        <td>{{ $key }}</td>
                                        @foreach ($locales as $locale)
                                            <td>
                                                <a href="#edit" class="editable status-{{ Arr::get($translation, $locale)->status ?? 0 }} locale-{{ $locale }}" data-locale="{{ $locale }}" data-name="{{ sprintf('%s|%s', $locale, $key) }}" id="username" data-type="textarea" data-pk="{{ Arr::get($translation, $locale)->id ?? 0 }}" data-url="{{ $editUrl }}">
                                                    {!! Arr::get($translation, $locale)->value ?? '' !!}
                                                </a>
                                            </td>
                                        @endforeach
                                        @if ($deleteEnabled)
                                            <td>
                                                <a href="{{ action('\Barryvdh\TranslationManager\Controller@postDelete', [$group, $key]) }}"
                                                class="delete-key delete btn btn-danger btn-sm"
                                                data-confirm="დარწმუნებული ხართ რომ გინდათ წაშლა?">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('style')
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
    <style>
        a.status-1{
            font-weight: bold;
        }
    </style>
@endsection

@section('script')

    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>

@endsection

@section('js')
    <script>
        (function(e, t) {
            if (e.rails !== t) {
                e.error("jquery-ujs has already been loaded!")
            }
            var n;
            var r = e(document);
            e.rails = n = {
                linkClickSelector: "a[data-confirm], a[data-method], a[data-remote], a[data-disable-with]",
                buttonClickSelector: "button[data-remote], button[data-confirm]",
                inputChangeSelector: "select[data-remote], input[data-remote], textarea[data-remote]",
                formSubmitSelector: "form",
                formInputClickSelector: "form input[type=submit], form input[type=image], form button[type=submit], form button:not([type])",
                disableSelector: "input[data-disable-with], button[data-disable-with], textarea[data-disable-with]",
                enableSelector: "input[data-disable-with]:disabled, button[data-disable-with]:disabled, textarea[data-disable-with]:disabled",
                requiredInputSelector: "input[name][required]:not([disabled]),textarea[name][required]:not([disabled])",
                fileInputSelector: "input[type=file]",
                linkDisableSelector: "a[data-disable-with]",
                buttonDisableSelector: "button[data-remote][data-disable-with]",
                CSRFProtection: function(t) {
                    var n = e('meta[name="csrf-token"]').attr("content");
                    if (n) t.setRequestHeader("X-CSRF-Token", n)
                },
                refreshCSRFTokens: function() {
                    var t = e("meta[name=csrf-token]").attr("content");
                    var n = e("meta[name=csrf-param]").attr("content");
                    e('form input[name="' + n + '"]').val(t)
                },
                fire: function(t, n, r) {
                    var i = e.Event(n);
                    t.trigger(i, r);
                    return i.result !== false
                },
                confirm: function(e) {
                    return confirm(e)
                },
                ajax: function(t) {
                    return e.ajax(t)
                },
                href: function(e) {
                    return e.attr("href")
                },
                handleRemote: function(r) {
                    var i, s, o, u, a, f, l, c;
                    if (n.fire(r, "ajax:before")) {
                        u = r.data("cross-domain");
                        a = u === t ? null : u;
                        f = r.data("with-credentials") || null;
                        l = r.data("type") || e.ajaxSettings && e.ajaxSettings.dataType;
                        if (r.is("form")) {
                            i = r.attr("method");
                            s = r.attr("action");
                            o = r.serializeArray();
                            var h = r.data("ujs:submit-button");
                            if (h) {
                                o.push(h);
                                r.data("ujs:submit-button", null)
                            }
                        } else if (r.is(n.inputChangeSelector)) {
                            i = r.data("method");
                            s = r.data("url");
                            o = r.serialize();
                            if (r.data("params")) o = o + "&" + r.data("params")
                        } else if (r.is(n.buttonClickSelector)) {
                            i = r.data("method") || "get";
                            s = r.data("url");
                            o = r.serialize();
                            if (r.data("params")) o = o + "&" + r.data("params")
                        } else {
                            i = r.data("method");
                            s = n.href(r);
                            o = r.data("params") || null
                        }
                        c = {
                            type: i || "GET",
                            data: o,
                            dataType: l,
                            beforeSend: function(e, i) {
                                if (i.dataType === t) {
                                    e.setRequestHeader("accept", "*/*;q=0.5, " + i.accepts.script)
                                }
                                if (n.fire(r, "ajax:beforeSend", [e, i])) {
                                    r.trigger("ajax:send", e)
                                } else {
                                    return false
                                }
                            },
                            success: function(e, t, n) {
                                r.trigger("ajax:success", [e, t, n])
                            },
                            complete: function(e, t) {
                                r.trigger("ajax:complete", [e, t])
                            },
                            error: function(e, t, n) {
                                r.trigger("ajax:error", [e, t, n])
                            },
                            crossDomain: a
                        };
                        if (f) {
                            c.xhrFields = {
                                withCredentials: f
                            }
                        }
                        if (s) {
                            c.url = s
                        }
                        return n.ajax(c)
                    } else {
                        return false
                    }
                },
                handleMethod: function(r) {
                    var i = n.href(r),
                        s = r.data("method"),
                        o = r.attr("target"),
                        u = e("meta[name=csrf-token]").attr("content"),
                        a = e("meta[name=csrf-param]").attr("content"),
                        f = e('<form method="post" action="' + i + '"></form>'),
                        l = '<input name="_method" value="' + s + '" type="hidden" />';
                    if (a !== t && u !== t) {
                        l += '<input name="' + a + '" value="' + u + '" type="hidden" />'
                    }
                    if (o) {
                        f.attr("target", o)
                    }
                    f.hide().append(l).appendTo("body");
                    f.submit()
                },
                formElements: function(t, n) {
                    return t.is("form") ? e(t[0].elements).filter(n) : t.find(n)
                },
                disableFormElements: function(t) {
                    n.formElements(t, n.disableSelector).each(function() {
                        n.disableFormElement(e(this))
                    })
                },
                disableFormElement: function(e) {
                    var t = e.is("button") ? "html" : "val";
                    e.data("ujs:enable-with", e[t]());
                    e[t](e.data("disable-with"));
                    e.prop("disabled", true)
                },
                enableFormElements: function(t) {
                    n.formElements(t, n.enableSelector).each(function() {
                        n.enableFormElement(e(this))
                    })
                },
                enableFormElement: function(e) {
                    var t = e.is("button") ? "html" : "val";
                    if (e.data("ujs:enable-with")) e[t](e.data("ujs:enable-with"));
                    e.prop("disabled", false)
                },
                allowAction: function(e) {
                    var t = e.data("confirm"),
                        r = false,
                        i;
                    if (!t) {
                        return true
                    }
                    if (n.fire(e, "confirm")) {
                        r = n.confirm(t);
                        i = n.fire(e, "confirm:complete", [r])
                    }
                    return r && i
                },
                blankInputs: function(t, n, r) {
                    var i = e(),
                        s, o, u = n || "input,textarea",
                        a = t.find(u);
                    a.each(function() {
                        s = e(this);
                        o = s.is("input[type=checkbox],input[type=radio]") ? s.is(":checked") : s.val();
                        if (!o === !r) {
                            if (s.is("input[type=radio]") && a.filter('input[type=radio]:checked[name="' + s.attr("name") + '"]').length) {
                                return true
                            }
                            i = i.add(s)
                        }
                    });
                    return i.length ? i : false
                },
                nonBlankInputs: function(e, t) {
                    return n.blankInputs(e, t, true)
                },
                stopEverything: function(t) {
                    e(t.target).trigger("ujs:everythingStopped");
                    t.stopImmediatePropagation();
                    return false
                },
                disableElement: function(e) {
                    e.data("ujs:enable-with", e.html());
                    e.html(e.data("disable-with"));
                    e.bind("click.railsDisable", function(e) {
                        return n.stopEverything(e)
                    })
                },
                enableElement: function(e) {
                    if (e.data("ujs:enable-with") !== t) {
                        e.html(e.data("ujs:enable-with"));
                        e.removeData("ujs:enable-with")
                    }
                    e.unbind("click.railsDisable")
                }
            };
            if (n.fire(r, "rails:attachBindings")) {
                e.ajaxPrefilter(function(e, t, r) {
                    if (!e.crossDomain) {
                        n.CSRFProtection(r)
                    }
                });
                r.delegate(n.linkDisableSelector, "ajax:complete", function() {
                    n.enableElement(e(this))
                });
                r.delegate(n.buttonDisableSelector, "ajax:complete", function() {
                    n.enableFormElement(e(this))
                });
                r.delegate(n.linkClickSelector, "click.rails", function(r) {
                    var i = e(this),
                        s = i.data("method"),
                        o = i.data("params"),
                        u = r.metaKey || r.ctrlKey;
                    if (!n.allowAction(i)) return n.stopEverything(r);
                    if (!u && i.is(n.linkDisableSelector)) n.disableElement(i);
                    if (i.data("remote") !== t) {
                        if (u && (!s || s === "GET") && !o) {
                            return true
                        }
                        var a = n.handleRemote(i);
                        if (a === false) {
                            n.enableElement(i)
                        } else {
                            a.error(function() {
                                n.enableElement(i)
                            })
                        }
                        return false
                    } else if (i.data("method")) {
                        n.handleMethod(i);
                        return false
                    }
                });
                r.delegate(n.buttonClickSelector, "click.rails", function(t) {
                    var r = e(this);
                    if (!n.allowAction(r)) return n.stopEverything(t);
                    if (r.is(n.buttonDisableSelector)) n.disableFormElement(r);
                    var i = n.handleRemote(r);
                    if (i === false) {
                        n.enableFormElement(r)
                    } else {
                        i.error(function() {
                            n.enableFormElement(r)
                        })
                    }
                    return false
                });
                r.delegate(n.inputChangeSelector, "change.rails", function(t) {
                    var r = e(this);
                    if (!n.allowAction(r)) return n.stopEverything(t);
                    n.handleRemote(r);
                    return false
                });
                r.delegate(n.formSubmitSelector, "submit.rails", function(r) {
                    var i = e(this),
                        s = i.data("remote") !== t,
                        o, u;
                    if (!n.allowAction(i)) return n.stopEverything(r);
                    if (i.attr("novalidate") == t) {
                        o = n.blankInputs(i, n.requiredInputSelector);
                        if (o && n.fire(i, "ajax:aborted:required", [o])) {
                            return n.stopEverything(r)
                        }
                    }
                    if (s) {
                        u = n.nonBlankInputs(i, n.fileInputSelector);
                        if (u) {
                            setTimeout(function() {
                                n.disableFormElements(i)
                            }, 13);
                            var a = n.fire(i, "ajax:aborted:file", [u]);
                            if (!a) {
                                setTimeout(function() {
                                    n.enableFormElements(i)
                                }, 13)
                            }
                            return a
                        }
                        n.handleRemote(i);
                        return false
                    } else {
                        setTimeout(function() {
                            n.disableFormElements(i)
                        }, 13)
                    }
                });
                r.delegate(n.formInputClickSelector, "click.rails", function(t) {
                    var r = e(this);
                    if (!n.allowAction(r)) return n.stopEverything(t);
                    var i = r.attr("name"),
                        s = i ? {
                            name: i,
                            value: r.val()
                        } : null;
                    r.closest("form").data("ujs:submit-button", s)
                });
                r.delegate(n.formSubmitSelector, "ajax:send.rails", function(t) {
                    if (this == t.target) n.disableFormElements(e(this))
                });
                r.delegate(n.formSubmitSelector, "ajax:complete.rails", function(t) {
                    if (this == t.target) n.enableFormElements(e(this))
                });
                e(function() {
                    n.refreshCSRFTokens()
                })
            }
        })(jQuery)

        jQuery(document).ready(function($){

            $.ajaxSetup({
                beforeSend: function(xhr, settings) {
                    console.log('beforesend');
                    settings.data += "&_token=<?= csrf_token() ?>";
                }
            });

            $('.editable').editable().on('hidden', function(e, reason){
                var locale = $(this).data('locale');
                if(reason === 'save'){
                    $(this).removeClass('status-0').addClass('status-1');
                }
                /*if(reason === 'save' || reason === 'nochange') {
                    var $next = $(this).closest('tr').next().find('.editable.locale-'+locale);
                    setTimeout(function() {
                        $next.editable('show');
                    }, 300);
                }*/
            });

            $('.group-select').on('change', function(){
                var group = $(this).val();
                if (group) {
                    window.location.href = '<?= action('\Barryvdh\TranslationManager\Controller@getView') ?>/'+$(this).val();
                } else {
                    window.location.href = '<?= action('\Barryvdh\TranslationManager\Controller@getIndex') ?>';
                }
            });

            $("a.delete-key").click(function(event){
              event.preventDefault();
              var row = $(this).closest('tr');
              var url = $(this).attr('href');
              var id = row.attr('id');
              $.post( url, {id: id}, function(){
                  row.remove();
              } );
            });

            $('.form-import').on('ajax:success', function (e, data) {
                $('div.success-import strong.counter').text(data.counter);
                $('div.success-import').slideDown();
            });

            $('.form-find').on('ajax:success', function (e, data) {
                $('div.success-find strong.counter').text(data.counter);
                $('div.success-find').slideDown();
            });

            $('.form-publish').on('ajax:success', function (e, data) {
                $('div.success-publish').slideDown();
            });

        })
    </script>
@endsection
