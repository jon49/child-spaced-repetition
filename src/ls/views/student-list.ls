require! <[
  ./components/html5
  ./components/setting
]>

require! {m: mithril}

selectStudent = ->
  m \div [
    m \a, (
      config: m.route
      href:   "/app/students/#{it.studentId}/quiz"
    ), [m \button it.studentName]
    m \button, {onclick: @editName.bind @, it.studentId}, \Edit
  ]

editStudent = (student, ...cb)->
  cb_ = cb.1 ? @newName.bind @, student.studentId
  m \div [
    m 'input#edit[type="text"]', (
      value: student.studentName
      config: (e) !->
        e.focus!
        e.onkeypress = ->
          | e.keyCode is 13 =>
            cb_
          | _ => #do nothing
    )
    m \button, {onclick: @newName.bind @, student.studentId}, \Submit
  ]

newStudent = ->
  | @newStudent =>
    newStudent = {}
    newStudent.studentId = -1
    newStudent.studentName = 'Student Name'
    editStudent.call @, newStudent, @createStudent.bind @
  | _ =>
    m \div.new-student [
      m \a, (
        onclick: @newStudent.bind @
      ), [m \button, 'New Student Name']
    ]

module.exports = (ctrl) ->
  students = ctrl.students or []
  result = html5(
    ['/css/app.css']
    setting 'Students', (students.map ->
      | it.edit =>
        editStudent.call ctrl, it
      | _ =>
        selectStudent.call ctrl, it
    ).concat newStudent.call @
  )
