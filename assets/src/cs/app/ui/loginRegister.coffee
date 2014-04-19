define [], ->

  class LoginRegister
    constructor : (@login, @register, @btn) ->
      $(@login.form).hide()
      $(@register.form).hide()
      $(@register.btn).addClass("btn #{@btn.passive}")
      $(@login.btn).addClass("btn #{@btn.passive}")
      @

    toggleBtn : ->
      $(@register.form).hide()
      $(@currentActive.btn).removeClass(@btn.passive)
      $(@currentActive.btn).addClass(@btn.active)
      $(@currentPassive.btn).removeClass(@btn.active)
      $(@currentPassive.btn).addClass(@btn.passive)
      $(@currentPassive.form).hide()
      if not @isActive()
        $(@currentActive.form).show()
      else
        $(@currentActive.form).hide()
        $(@currentActive.btn).removeClass(@btn.active)
        $(@currentActive.btn).addClass(@btn.passive)
        @currentActive = {}
    
    isActive : ->
      @previousActive.form is @currentActive.form
    
    setActive : (active) ->
      @previousActive = @currentActive or {}
      if active is "login"
        @currentActive = @login
        @currentPassive = @register
      else
        @currentActive = @register
        @currentPassive = @login
      [@currentActive, @currentPassive]

