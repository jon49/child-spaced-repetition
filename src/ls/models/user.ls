require! {m: mithril}

User = {}

User.students = ->
  m.request {method: 'GET', url: '/api/students'}

module.exports = User
