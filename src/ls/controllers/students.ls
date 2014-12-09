require! {
  m: mithril
  User: './../models/user'
}

!function Controller
  @students = User.students!

module.exports = Controller
