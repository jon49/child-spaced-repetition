require! {
  m: mithril
  r: ramda
  User: './../models/user'
}

class Controller

  ->
    self = @
    User.students! .then (data) !->
      self.students = data

  studentIndex: (studentId) ->
    @students
    |> r.findIndex r.propEq \studentId studentId

  editName: (studentId) ->
    editIndex = @studentIndex studentId
    @students[editIndex].edit = true

  newName: (studentId) !->
    studentIndex = @studentIndex studentId
    switch $edit = document.getElementById \edit
    | true =>
      | newName = $edit.value =>
        User.changeStudentName studentId, newName
        @students[studentIndex].edit = false
        @students[studentIndex].studentName = newName
      | _ =>
        User.deleteStudent studentId
        @students.splice studentIndex, 1
    | _ => # do nothing

  newStudent: ->
    @newStudent = true

  createStudent: ->
    self = @
    switch $edit = document.getElementById \edit
    | true and $edit.value =>
      User.createStudent (
        studentName: $edit.value
      ).then (data) ->
        self.students.push data
    | _ => # do nothing

module.exports = Controller
