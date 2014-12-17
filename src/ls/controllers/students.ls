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
    $edit = document.getElementById \edit
    newName = if ($edit) then $edit.value else ''
    switch !!newName
    | true =>
      User.changeStudentName studentId, newName
      @students[studentIndex].edit = false
      @students[studentIndex].studentName = newName
    | _ =>
      User.deleteStudent studentId
      @students.splice studentIndex, 1

  editNewStudent: ->
    @newStudent = true

  createStudent: ->
    self = @
    @newStudent = false
    $edit = document.getElementById \edit
    studentName = if $edit then $edit.value else ''
    switch !!studentName
    | true =>
      User.createStudent studentName .then (data) !->
        self.students.push data
    | _ => # do nothing

module.exports = Controller
