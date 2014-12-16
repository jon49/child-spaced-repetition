require! {m: mithril}

Student = {}

Student.cards = (studentId) ->
  m.request {method: \GET, url: "/api/students/#{studentId}/cards"}

Student.sendStudentResult = (performance) ->
  studentId = m.route.param(\id)
  m.request (
    method: \PUT
    url: "/api/students/#{studentId}/cards"
    data: {cards: performance}
  )

module.exports = Student
