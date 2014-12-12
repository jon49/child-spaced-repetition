require! {m: mithril}

Student = {}
Student.store = {}

Student.cards = (studentId) ->
  m.request {method: 'GET', url: "/api/students/#{studentId}/cards"}

Student.sendStudentResults = ->
  m.request (
    method: 'POST'
    url: "/api/cards/#{studentId}"
    serialize: Student.store.cards
  )

module.exports = Student
