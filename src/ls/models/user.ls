require! {m: mithril}

User = {}

User.students = ->
  m.request {method: 'GET', url: '/api/students'}

User.changeStudentName = (studentId, newName) ->
  m.request (
    method: \PUT
    url: "/api/students/#{studentId}"
    data: {studentName: newName}
  )

User.deleteStudent = (studentId) ->
  m.request (
    method: \DELETE
    url: "/api/students/#{studentId}"
  )

module.exports = User
