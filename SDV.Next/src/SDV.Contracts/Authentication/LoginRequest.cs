using System.ComponentModel.DataAnnotations;
namespace SDV.Contracts.Authentication;
public sealed record LoginRequest([Required, StringLength(100)] string Username, [Required, StringLength(256)] string Password);

